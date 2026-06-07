<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Food;
use App\Models\FoodHistory;
use App\Models\MealReminder;
use App\Models\User;
use App\Models\UserAllergy;
use App\Models\UserMedicalNeed;
use App\Services\CalorieCalculatorService;
use App\Services\MenuRecommendationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class DashboardController extends Controller {

    public function __construct(
        private MenuRecommendationService $menuService,
        private CalorieCalculatorService $calorieService
    ) {}

    public function index(Request $request) {
        $user          = Auth::user();

        if (!$user) {
            $guestInput = $request->only(['age', 'height_cm', 'weight_kg', 'activity_level', 'province']);
            $guestSummary = null;
            $guestRecommendations = collect();

            $featuredFoods = Food::where('is_active', true)
                ->latest()
                ->take(3)
                ->get();

            $articles = Article::where('is_published', true)
                ->latest()
                ->take(3)
                ->get();

            $activityOptions = [
                ['value' => 'sedentary', 'label' => 'Ringan', 'description' => 'Duduk, kerja di depan laptop, atau aktivitas harian yang sangat minim.'],
                ['value' => 'light', 'label' => 'Sedikit aktif', 'description' => 'Ada jalan kaki ringan atau aktivitas rumah seperlunya.'],
                ['value' => 'moderate', 'label' => 'Cukup aktif', 'description' => 'Olahraga ringan sampai sedang sekitar 3-5x per minggu.'],
                ['value' => 'active', 'label' => 'Aktif', 'description' => 'Latihan rutin dengan intensitas tinggi atau aktivitas fisik yang sering.'],
                ['value' => 'very_active', 'label' => 'Sangat aktif', 'description' => 'Kerja fisik berat, olahraga rutin, atau aktivitas harian sangat padat.'],
            ];

            $allergyOptions = [
                ['value' => 'gluten', 'label' => 'Gluten'],
                ['value' => 'seafood', 'label' => 'Seafood'],
                ['value' => 'shellfish', 'label' => 'Kerang / Udang'],
                ['value' => 'nuts', 'label' => 'Kacang-kacangan'],
                ['value' => 'peanuts', 'label' => 'Kacang tanah'],
                ['value' => 'dairy', 'label' => 'Susu / Dairy'],
                ['value' => 'eggs', 'label' => 'Telur'],
                ['value' => 'soy', 'label' => 'Kedelai'],
                ['value' => 'sesame', 'label' => 'Wijen'],
                ['value' => 'wheat', 'label' => 'Gandum'],
                ['value' => 'kacang', 'label' => 'Kacang'],
                ['value' => 'susu', 'label' => 'Susu'],
                ['value' => 'telur', 'label' => 'Telur'],
                ['value' => 'udang', 'label' => 'Udang'],
                ['value' => 'kepiting', 'label' => 'Kepiting'],
                ['value' => 'cumi', 'label' => 'Cumi-cumi'],
            ];

            if ($request->boolean('guest_preview') && collect($guestInput)->filter(fn ($value) => $value !== null && $value !== '')->count() >= 5) {
                $request->validate([
                    'age' => 'required|integer|min:1|max:120',
                    'height_cm' => 'required|numeric|min:50|max:300',
                    'weight_kg' => 'required|numeric|min:10|max:500',
                    'activity_level' => 'required|in:sedentary,light,moderate,active,very_active',
                    'province' => 'required|string|max:100',
                ]);

                $bmi = $this->calorieService->calculateBMI((float) $request->weight_kg, (float) $request->height_cm);
                $bmr = $this->calorieService->calculateBMR((float) $request->weight_kg, (float) $request->height_cm, (int) $request->age);
                $dailyCalories = $this->calorieService->calculateTDEE($bmr, $request->activity_level);

                $guestSummary = [
                    'age' => (int) $request->age,
                    'height_cm' => (float) $request->height_cm,
                    'weight_kg' => (float) $request->weight_kg,
                    'activity_level' => $request->activity_level,
                    'province' => $request->province,
                    'bmi' => $bmi,
                    'bmi_category' => $this->calorieService->getBMICategory($bmi),
                    'daily_calories' => round($dailyCalories),
                    'meal_calories' => $this->calorieService->getMealCalories($dailyCalories),
                ];

                $guestUser = new User([
                    'province' => $request->province,
                    'bmi' => $bmi,
                    'daily_calorie_needs' => round($dailyCalories),
                    'activity_level' => $request->activity_level,
                ]);
                $guestUser->setRelation('allergies', collect());

                $guestRecommendations = Food::query()
                    ->where('is_active', true)
                    ->when($request->province, fn ($query) => $query->where('origin', 'like', '%' . $request->province . '%'))
                    ->orderByRaw('ABS(calories - ?) ASC', [max(250, round($dailyCalories / 3))])
                    ->take(3)
                    ->get();
            } else {
                $guestRecommendations = Food::query()
                    ->where('is_active', true)
                    ->when($request->province, fn ($query) => $query->where('origin', 'like', '%' . $request->province . '%'))
                    ->latest()
                    ->take(3)
                    ->get();
            }

            return view('guest.dashboard', compact(
                'featuredFoods',
                'articles',
                'activityOptions',
                'allergyOptions',
                'guestInput',
                'guestSummary',
                'guestRecommendations'
            ));
        }

        /** @var User $user */
        $today         = Carbon::today();
        $recommendation = $this->menuService->generateDailyMenu($user);

        $todayCalories = FoodHistory::where('user_id', $user->id)
            ->where('consumed_date', $today)
            ->sum('calories_consumed');

        $weeklyHistory = FoodHistory::where('user_id', $user->id)
            ->whereBetween('consumed_date', [$today->copy()->subDays(6), $today])
            ->selectRaw('consumed_date, SUM(calories_consumed) as total')
            ->groupBy('consumed_date')
            ->orderBy('consumed_date')
            ->get();

        $articles = Article::where('is_published', true)
            ->latest()
            ->take(3)
            ->get();

        $allergies = $user->allergies()->pluck('allergen');

        // Ensure default reminder rows exist for every user
        // (prevents empty reminder timeline for existing users)
        $hasAnyActiveReminders = $user->reminders()->where('is_active', true)->exists();
        if (!$hasAnyActiveReminders) {
            MealReminder::updateOrCreate(
                ['user_id' => $user->id, 'meal_type' => 'breakfast'],
                ['reminder_time' => '07:00:00', 'is_active' => true]
            );
            MealReminder::updateOrCreate(
                ['user_id' => $user->id, 'meal_type' => 'lunch'],
                ['reminder_time' => '12:00:00', 'is_active' => true]
            );
            MealReminder::updateOrCreate(
                ['user_id' => $user->id, 'meal_type' => 'dinner'],
                ['reminder_time' => '18:30:00', 'is_active' => true]
            );
        }

        // Keep deterministic timeline order: breakfast -> lunch -> dinner
        $reminders = $user->reminders()
            ->where('is_active', true)
            ->orderByRaw("CASE meal_type
                WHEN 'breakfast' THEN 1
                WHEN 'lunch' THEN 2
                WHEN 'dinner' THEN 3
                ELSE 99
            END")
            ->get();



        // Planned menus for reminders (menu_recommendations)
        // Both recommendation cards and reminder cards must read from the same planned state.
        // Fallback: if the user has no saved planned menu yet, use generated recommendations.
        $menuRec = [
            'breakfast' => null,
            'lunch' => null,
            'dinner' => null,
        ];

        $todayMenuRec = \App\Models\MenuRecommendation::where('user_id', $user->id)
            ->where('recommendation_date', $today)
            ->first();

        if ($todayMenuRec) {
            $menuRec['breakfast'] = $todayMenuRec->breakfast_id ? \App\Models\Food::find($todayMenuRec->breakfast_id) : null;
            $menuRec['lunch']     = $todayMenuRec->lunch_id ? \App\Models\Food::find($todayMenuRec->lunch_id) : null;
            $menuRec['dinner']    = $todayMenuRec->dinner_id ? \App\Models\Food::find($todayMenuRec->dinner_id) : null;
        } else {
            // Planned menu should be driven by menu_recommendations once generated.
            // Avoid falling back to generated recommendations here, because it can desync
            // the UI from what was just saved.
            $menuRec = [
                'breakfast' => null,
                'lunch'     => null,
                'dinner'    => null,
            ];
        }


        // Completed histories for today (food_histories)
        $todayHistories = FoodHistory::where('user_id', $user->id)
            ->where('consumed_date', $today)
            ->get();

        $historyByMealType = $todayHistories->keyBy('meal_type');

        $unreadNotifications = $user->notifications()->where('is_read', false)->count();
        $needsOnboarding = !$user->onboarding_completed;
        $provinceOptions = config('nutrigo.provinces');
        $allergyOptions = config('nutrigo.allergens');
        $activityOptions = [
            ['value' => 'sedentary', 'label' => 'Ringan'],
            ['value' => 'light', 'label' => 'Sedikit aktif'],
            ['value' => 'moderate', 'label' => 'Cukup aktif'],
            ['value' => 'active', 'label' => 'Aktif'],
            ['value' => 'very_active', 'label' => 'Sangat aktif'],
        ];

        return view('user.dashboard', compact(
            'user','recommendation','todayCalories','allergies',
            'weeklyHistory','articles','reminders','unreadNotifications','needsOnboarding',
            'provinceOptions','allergyOptions','activityOptions',
            'menuRec','historyByMealType'
        ));

    }

    public function filterRecommendations(Request $request)
    {
        $request->validate([
            'province' => 'required|string',
            'activity_level' => 'nullable|in:sedentary,light,moderate,active,very_active',
            'allergens' => 'nullable|array',
        ]);

        /** @var User $user */
        $user = Auth::user();

        $filters = [
            'province' => $request->province,
            'activity_level' => $request->activity_level,
            'allergens' => $request->allergens ?? null,
        ];

        $menu = $this->menuService->generateDailyMenuWithFilters($user, $filters);

        $html = view('user.partials.recommendation_cards', ['menu' => $menu])->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

    public function completeProfile(Request $request)
    {
        $request->validate([
            'nickname' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'has_allergy' => 'required|in:yes,no',
            'allergens' => 'nullable|array',
            'allergens.*' => 'string|max:100',
            'custom_allergy' => 'nullable|string|max:100',
            'has_medical_need' => 'required|in:yes,no',
            'food_item' => 'required_if:has_medical_need,yes|string|max:255',
            'quantity' => 'required_if:has_medical_need,yes|integer|min:1',
            'unit' => 'required_if:has_medical_need,yes|string|max:50',
            'duration_type' => 'required_if:has_medical_need,yes|in:daily,weekly,yearly,forever',
        ]);

        $user = Auth::user();
        $allergens = collect($request->input('allergens', []))
            ->filter()
            ->values()
            ->all();

        if ($request->filled('custom_allergy')) {
            $allergens[] = trim($request->custom_allergy);
        }

        $allergens = array_values(array_unique(array_filter($allergens)));

        /** @var User $user */
        $user->update([
            'nickname' => $request->nickname,
            'province' => $request->province,
            'city' => $request->city,
            'onboarding_completed' => true,
            'onboarding_step' => 5,
        ]);

        $user->allergies()->delete();
        if ($request->has_allergy === 'yes') {
            foreach ($allergens as $allergen) {
                UserAllergy::create(['user_id' => $user->id, 'allergen' => $allergen]);
            }
        }

        if ($request->has_medical_need === 'yes') {
            $user->medicalNeeds()->update(['is_active' => false]);
            UserMedicalNeed::updateOrCreate(
                ['user_id' => $user->id, 'is_active' => true],
                [
                    'food_item' => $request->food_item,
                    'quantity' => $request->quantity,
                    'unit' => $request->unit,
                    'duration_type' => $request->duration_type,
                    'start_date' => now()->toDateString(),
                    'end_date' => null,
                    'is_active' => true,
                ]
            );
        } else {
            $user->medicalNeeds()->update(['is_active' => false]);
        }

        MealReminder::updateOrCreate(
            ['user_id' => $user->id, 'meal_type' => 'breakfast'],
            ['reminder_time' => '07:00:00', 'is_active' => true]
        );
        MealReminder::updateOrCreate(
            ['user_id' => $user->id, 'meal_type' => 'lunch'],
            ['reminder_time' => '12:00:00', 'is_active' => true]
        );
        MealReminder::updateOrCreate(
            ['user_id' => $user->id, 'meal_type' => 'dinner'],
            ['reminder_time' => '18:30:00', 'is_active' => true]
        );

        return redirect()->route('user.dashboard')->with('welcome', true);
    }
}