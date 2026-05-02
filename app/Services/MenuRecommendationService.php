<?php
namespace App\Services;

use App\Models\Food;
use App\Models\User;
use App\Models\MenuRecommendation;
use App\Models\FoodHistory;
use Carbon\Carbon;

class MenuRecommendationService {

    public function __construct(
        private CalorieCalculatorService $calorieService,
        private AllergyFilterService $allergyService
    ) {}

    /**
     * Generate rekomendasi menu untuk hari ini
     */
    public function generateDailyMenu(User $user): MenuRecommendation {
        $today = Carbon::today();

        // Cek apakah sudah ada rekomendasi hari ini
        $existing = MenuRecommendation::where('user_id', $user->id)
            ->where('recommendation_date', $today)
            ->first();

        if ($existing) return $existing;

        $mealCalories  = $this->calorieService->getMealCalories($user->daily_calorie_needs ?? 2000);
        $userAllergens = $user->allergies->pluck('allergen')->toArray();
        $recentFoodIds = $this->getRecentFoodIds($user, 3); // avoid repeat 3 hari

        $breakfast = $this->pickFood('breakfast', $mealCalories['breakfast'], $userAllergens, $recentFoodIds, $user->province);
        $lunch     = $this->pickFood('lunch',     $mealCalories['lunch'],     $userAllergens, array_merge($recentFoodIds, [$breakfast?->id]), $user->province);
        $dinner    = $this->pickFood('dinner',    $mealCalories['dinner'],    $userAllergens, array_merge($recentFoodIds, [$breakfast?->id, $lunch?->id]), $user->province);

        $totalCalories = ($breakfast?->calories ?? 0) + ($lunch?->calories ?? 0) + ($dinner?->calories ?? 0);

        return MenuRecommendation::create([
            'user_id'              => $user->id,
            'recommendation_date'  => $today,
            'breakfast_id'         => $breakfast?->id,
            'lunch_id'             => $lunch?->id,
            'dinner_id'            => $dinner?->id,
            'total_calories'       => $totalCalories,
        ]);
    }

    private function pickFood(string $mealType, float $targetCalories, array $allergens, array $excludeIds, ?string $province): ?Food {
        $query = Food::where('is_active', true)
            ->where('meal_type', $mealType)
            ->whereNotIn('id', array_filter($excludeIds));

        // Filter alergen
        foreach ($allergens as $allergen) {
            $query->where('composition', 'not like', "%{$allergen}%");
        }

        // Prioritaskan makanan dari wilayah user
        $foods = $query->get();

        if ($foods->isEmpty()) {
            // Fallback: ambil tanpa filter wilayah
            $foods = Food::where('is_active', true)
                ->where('meal_type', $mealType)
                ->get();
        }

        if ($foods->isEmpty()) return null;

        // Pilih makanan dengan kalori paling mendekati target (±30%)
        $min = $targetCalories * 0.7;
        $max = $targetCalories * 1.3;

        $suitable = $foods->filter(fn($f) => $f->calories >= $min && $f->calories <= $max);

        // Prioritas wilayah lokal
        $local = $suitable->filter(fn($f) => str_contains(strtolower($f->origin ?? ''), strtolower($province ?? '')));

        if ($local->isNotEmpty()) return $local->random();
        if ($suitable->isNotEmpty()) return $suitable->random();

        return $foods->sortBy(fn($f) => abs($f->calories - $targetCalories))->first();
    }

    private function getRecentFoodIds(User $user, int $days): array {
        $since = Carbon::now()->subDays($days);
        return FoodHistory::where('user_id', $user->id)
            ->where('consumed_date', '>=', $since)
            ->pluck('food_id')
            ->toArray();
    }
}