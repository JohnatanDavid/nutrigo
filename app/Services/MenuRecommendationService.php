<?php
namespace App\Services;

use App\Models\Food;
use App\Models\User;
use App\Models\MenuRecommendation;
use App\Models\FoodHistory;
use Carbon\Carbon;
use Illuminate\Support\Str;

class MenuRecommendationService
{
    public function __construct(private CalorieCalculatorService $calorieService) {}

    /**
     * Generate rekomendasi menu harian untuk user
     * Menggunakan Content-Based Filtering dari train_data
     */
    public function generateDailyMenu(User $user): MenuRecommendation
    {
        $today = Carbon::today();

        // Cek jika sudah ada hari ini
        $existing = MenuRecommendation::where('user_id', $user->id)
            ->where('recommendation_date', $today)
            ->first();
        if ($existing) return $existing;

        // Hitung target kalori per waktu makan
        $dailyCal  = $user->daily_calorie_needs ?? 2000;
        $targets   = [
            'breakfast' => $dailyCal * 0.30,
            'lunch'     => $dailyCal * 0.40,
            'dinner'    => $dailyCal * 0.30,
        ];

        // Ambil alergen user
        $allergens = $user->allergies->pluck('allergen')->toArray();

        // Ambil makanan yang sudah dimakan 3 hari terakhir (untuk variasi)
        $recentIds = FoodHistory::where('user_id', $user->id)
            ->where('consumed_date', '>=', Carbon::now()->subDays(3))
            ->pluck('food_id')
            ->toArray();

        // Pilih menu berdasarkan profil user (Content-Based dari train_data)
        $breakfast = $this->pickFoodByProfile($user, 'breakfast', $targets['breakfast'], $allergens, $recentIds);
        $lunch     = $this->pickFoodByProfile($user, 'lunch',     $targets['lunch'],     $allergens, array_merge($recentIds, [$breakfast?->id]));
        $dinner    = $this->pickFoodByProfile($user, 'dinner',    $targets['dinner'],    $allergens, array_merge($recentIds, [$breakfast?->id, $lunch?->id]));

        $total = ($breakfast?->calories ?? 0) + ($lunch?->calories ?? 0) + ($dinner?->calories ?? 0);

        return MenuRecommendation::create([
            'user_id'             => $user->id,
            'recommendation_date' => $today,
            'breakfast_id'        => $breakfast?->id,
            'lunch_id'            => $lunch?->id,
            'dinner_id'           => $dinner?->id,
            'total_calories'      => $total,
        ]);
    }

    /**
     * INTI SISTEM REKOMENDASI:
     * Pilih makanan berdasarkan profil user (BMI, gender, usia)
     * + filter alergi + filter wilayah + variasi (anti-repeat)
     *
     * Logika ini terinspirasi dari train_data.csv yang
     * menggabungkan profil user (Sex, Age, Height, Weight, BMI)
     * dengan data makanan (calories, proteins, fat, carbohydrate)
     */
    private function pickFoodByProfile(
        User   $user,
        string $mealType,
        float  $targetCalories,
        array  $allergens,
        array  $excludeIds
    ): ?Food {

        // ── 1. Tentukan range kalori berdasarkan profil ────────────
        $bmi    = $user->bmi ?? 22;
        $gender = $user->gender ?? 'male';
        $age    = $user->getAge();

        // Toleransi kalori per porsi (berdasarkan pola di train_data)
        // BMI tinggi → makanan lebih rendah kalori
        // BMI rendah → makanan lebih tinggi kalori
        $tolerance = match(true) {
            $bmi < 18.5 => 0.40, // underweight: rentang lebar, terima makanan lebih kalori
            $bmi < 25.0 => 0.30, // normal: rentang standar
            $bmi < 30.0 => 0.25, // overweight: lebih ketat, prioritas rendah lemak
            default     => 0.20, // obese: sangat ketat
        };

        $minCal = $targetCalories * (1 - $tolerance);
        $maxCal = $targetCalories * (1 + $tolerance);

        // ── 2. Bangun query dasar ─────────────────────────────────
        $query = Food::where('is_active', true)
            // Slot-based recommendation: do NOT hard-filter by foods.meal_type.
            // $mealType is only used as a slot label to apply calorie targeting.
            ->where('calories', '>=', $minCal)
            ->where('calories', '<=', $maxCal)
            ->whereNotIn('id', array_filter($excludeIds));

        // ── 3. Filter alergen ──────────────────────────────────────
        foreach ($allergens as $allergen) {
            $query->whereRaw('LOWER(composition) NOT LIKE ?', ['%' . mb_strtolower($allergen) . '%']);
        }

        // ── 4. Preferensi berdasarkan BMI ─────────────────────────
        if ($bmi >= 25) {
            // Overweight/obese: prioritaskan makanan rendah lemak
            $query->orderBy('fat', 'asc');
        } elseif ($bmi < 18.5) {
            // Underweight: prioritaskan makanan tinggi protein
            $query->orderBy('proteins', 'desc');
        }

        // ── 5. Prioritas wilayah lokal ────────────────────────────
        $foods = $query->get();
        $foods = $foods->filter(fn (Food $food) => !$this->foodContainsAnyAllergen($food->composition ?? '', $allergens));

        if ($foods->isEmpty()) {
            // Fallback: cari tanpa filter range kalori
            $foods = Food::where('is_active', true)
                ->where('meal_type', $mealType)
                ->whereNotIn('id', array_filter($excludeIds))
                ->get();

            // Filter alergen manual
            $foods = $foods->filter(fn (Food $food) => !$this->foodContainsAnyAllergen($food->composition ?? '', $allergens));
        }

        // If still no candidates after strict filtering, broaden tolerance once (prevents dinner null)
        if ($foods->isEmpty()) {
            $broadTolerance = min(0.45, $tolerance + 0.15);
            $minCalBroad = $targetCalories * (1 - $broadTolerance);
            $maxCalBroad = $targetCalories * (1 + $broadTolerance);

            $queryBroad = Food::where('is_active', true)
                ->where('meal_type', $mealType)
                ->where('calories', '>=', $minCalBroad)
                ->where('calories', '<=', $maxCalBroad)
                ->whereNotIn('id', array_filter($excludeIds));

            foreach ($allergens as $allergen) {
                $queryBroad->whereRaw('LOWER(composition) NOT LIKE ?', ['%' . mb_strtolower($allergen) . '%']);
            }

            $foods = $queryBroad->get();
            $foods = $foods->filter(fn (Food $food) => !$this->foodContainsAnyAllergen($food->composition ?? '', $allergens));

            if ($foods->isEmpty()) return null;
        }


        // ── 6. Scoring sistem: priority strict (Province -> MainMeal -> National -> Others) ─────────
        $province = $user->province ?? '';

        // medical needs as boosts only (NOT filters)
        $medicalBoostItems = [];
        if (method_exists($user, 'medicalNeeds')) {
            $medicalNeeds = $user->medicalNeeds()
                ->where('is_active', true)
                ->get();

            $medicalBoostItems = $medicalNeeds
                ->pluck('food_item')
                ->filter()
                ->map(fn ($v) => mb_strtolower($v))
                ->values()
                ->all();
        }


        $scored = $foods->map(function (Food $food) use ($province, $targetCalories, $bmi, $medicalBoostItems) {
            $score = 0;

            // Priority 1: Province/region foods first (strong)
            if ($province && $this->stringContainsOrigin($food->origin ?? '', $province)) {
                $score += 1000;
            }

            // Priority 2: Main meal foods (strong)
            if (($food->food_category ?? null) === 'main_meal') {
                $score += 500;
            }

            // Priority 3: National foods (fallback)
            if (!empty($food->is_national)) {
                $score += 200;
            }

            // Priority 4: Other categories (low)
            if (($food->food_category ?? null) !== 'main_meal' && empty($food->is_national)) {
                $score += 25;
            }

            // Medical needs boosts (soft)
            if (!empty($medicalBoostItems)) {
                $composition = mb_strtolower($food->composition ?? '');
                foreach ($medicalBoostItems as $item) {
                    if ($item && $item !== '' && str_contains($composition, $item)) {
                        $score += 60;
                    }
                }
            }

            // Calories closeness (secondary)
            $calorieDiff = abs($food->calories - $targetCalories);
            $score += max(0, 100 - ($calorieDiff / 5));

            // BMI preferences (still secondary)
            if ($bmi < 18.5 && ($food->proteins ?? 0) > 15) {
                $score += 30;
            }
            if ($bmi >= 25 && ($food->fat ?? 0) < 10) {
                $score += 30;
            }

            return ['food' => $food, 'score' => $score];
        });


        // Ambil top 5, lalu pilih random dari top 5 (agar ada variasi)
        $top5 = $scored->sortByDesc('score')->take(5)->pluck('food');
        return $top5->random();
    }

    /**
     * Regenerate menu (hapus menu hari ini, buat baru)
     */
    public function regenerateMenu(User $user): MenuRecommendation
    {
        MenuRecommendation::where('user_id', $user->id)
            ->where('recommendation_date', Carbon::today())
            ->delete();
        return $this->generateDailyMenu($user);
    }

    /**
     * Generate rekomendasi berdasarkan filter (wilayah / aktivitas / alergi)
     * Mengembalikan array dengan keys: breakfast, lunch, dinner (Food|null)
     */
    public function generateDailyMenuWithFilters(User $user, array $filters = []): array
    {
        $today = Carbon::today();

        $dailyCal = $user->daily_calorie_needs ?? 2000;
        $targets   = [
            'breakfast' => $dailyCal * 0.30,
            'lunch'     => $dailyCal * 0.40,
            'dinner'    => $dailyCal * 0.30,
        ];

        $allergens = $filters['allergens'] ?? $user->allergies->pluck('allergen')->toArray();
        $provinceOverride = $filters['province'] ?? null;

        // recent eaten ids
        $recentIds = FoodHistory::where('user_id', $user->id)
            ->where('consumed_date', '>=', Carbon::now()->subDays(3))
            ->pluck('food_id')
            ->toArray();

        // untuk scoring, kita butuh user dengan province override
        $scoringUser = clone $user;
        if ($provinceOverride) $scoringUser->province = $provinceOverride;

        $breakfast = $this->pickFoodByProfile($scoringUser, 'breakfast', $targets['breakfast'], $allergens, $recentIds);
        $lunch = $this->pickFoodByProfile($scoringUser, 'lunch', $targets['lunch'], $allergens, array_merge($recentIds, [$breakfast?->id]));
        $dinner = $this->pickFoodByProfile($scoringUser, 'dinner', $targets['dinner'], $allergens, array_merge($recentIds, [$breakfast?->id, $lunch?->id]));

        return ['breakfast' => $breakfast, 'lunch' => $lunch, 'dinner' => $dinner];
    }

    /**
     * Cek apakah makanan cocok untuk user (skor kompatibilitas)
     */
    public function getFoodCompatibilityScore(Food $food, User $user): array
    {
        $allergens  = $user->allergies->pluck('allergen')->toArray();
        $bmi        = $user->bmi ?? 22;
        $issues     = [];
        $score      = 100;

        // Cek alergen
        foreach ($allergens as $a) {
            if ($this->compositionMatchesAllergen($food->composition ?? '', $a)) {
                $issues[] = "⚠️ Mengandung {$a} (alergenmu)";
                $score   -= 50;
            }
        }

        // Cek lemak untuk overweight
        if ($bmi >= 25 && $food->fat > 20) {
            $issues[] = "⚠️ Lemak cukup tinggi ({$food->fat}g) untuk BMI-mu";
            $score   -= 15;
        }

        // Cek kalori
        $dailyCal = $user->daily_calorie_needs ?? 2000;
        if ($food->calories > $dailyCal * 0.5) {
            $issues[] = "⚠️ Kalori sangat tinggi untuk 1 porsi";
            $score   -= 10;
        }

        return [
            'score'     => max(0, $score),
            'issues'    => $issues,
            'is_safe'   => empty(array_filter($issues, fn($i) => str_contains($i, 'alergen'))),
            'grade'     => match(true) {
                $score >= 90 => '✅ Sangat cocok',
                $score >= 70 => '👍 Cocok',
                $score >= 50 => '⚠️ Cukup cocok',
                default      => '❌ Kurang cocok',
            },
        ];
    }

    private function compositionMatchesAllergen(string $composition, string $allergen): bool
    {
        $needle = $this->normalizeCompositionLabel($allergen);
        if ($needle === null) {
            return false;
        }

        $parts = preg_split('/[,;\|]/', $composition) ?: [];

        foreach ($parts as $part) {
            $normalizedPart = $this->normalizeCompositionLabel($part);

            if ($normalizedPart !== null && $normalizedPart === $needle) {
                return true;
            }
        }

        return false;
    }

    private function foodContainsAnyAllergen(string $composition, array $allergens): bool
    {
        foreach ($allergens as $allergen) {
            if ($this->compositionMatchesAllergen($composition, $allergen)) {
                return true;
            }
        }

        return false;
    }

    private function stringContainsOrigin(string $origin, string $province): bool
    {
        $origin = mb_strtolower($origin);
        $province = mb_strtolower($province);

        if (!$province) return false;

        return str_contains($origin, $province);
    }

    private function normalizeCompositionLabel(string $value): ?string
    {

        $value = trim(preg_replace('/\s+/u', ' ', mb_strtolower($value)) ?? '');

        if ($value === '') {
            return null;
        }

        $value = preg_replace('/[^\p{L}\p{N}\s\/\-]/u', '', $value) ?? $value;

        $rules = [
            'Daging Ayam' => ['daging ayam', 'ayam', 'chicken'],
            'Daging Sapi' => ['daging sapi', 'sapi', 'beef'],
            'Ikan' => ['ikan', 'fish'],
            'Kedelai' => ['kedelai', 'soy', 'soya'],
            'Kacang' => ['kacang', 'peanut', 'nuts'],
            'Susu' => ['susu', 'dairy', 'milk'],
            'Telur' => ['telur', 'egg', 'eggs'],
            'Udang' => ['udang', 'shrimp'],
            'Kepiting' => ['kepiting', 'crab'],
            'Cumi' => ['cumi', 'squid'],
            'Gluten' => ['gluten', 'gandum', 'wheat'],
        ];

        foreach ($rules as $canonical => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($value, $keyword)) {
                    return $canonical;
                }
            }
        }

        return Str::title($value);
    }
}