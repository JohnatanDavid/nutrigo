<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\MealReminder;
use App\Models\User;
use App\Models\UserAllergy;
use App\Models\UserMedicalNeed;
use App\Models\Food;
use App\Services\CalorieCalculatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class OnboardingController extends Controller {

    public function __construct(private CalorieCalculatorService $calorieService) {}

    public function showStep(int $step) {
        /** @var User $user */
$user = Auth::user();
        if ($user->onboarding_completed) return redirect()->route('user.dashboard');

        // If user already has core health data from guest draft, skip health steps and
        // send them to allergies / special needs steps (3 and 4) only.
        $hasHealth = $user->height_cm || $user->weight_kg || $user->bmi || $user->daily_calorie_needs || $user->activity_level || $user->birth_date;

        if ($hasHealth) {
            // If requested step is the health-data step (5), redirect to preferences (step 2)
            if ($step === 5) {
                return redirect()->route('onboarding.step', ['step' => 2]);
            }
        }

        $viewData = compact('user');
        $viewData['currentStep'] = $step;

        // Provide composition options for allergy section on combined preferences (step 2) and legacy allergy step (step 3)
        if (in_array($step, [2, 3])) {
            $compositions = Food::whereNotNull('composition')
                ->pluck('composition')
                ->flatMap(function ($c) {
                    return array_map('trim', preg_split('/[,;\|]/', $c));
                })
                ->map(fn ($composition) => $this->normalizeCompositionLabel($composition))
                ->filter()
                ->unique()
                ->values()
                ->all();

            sort($compositions, SORT_NATURAL | SORT_FLAG_CASE);

            $viewData['compositionOptions'] = $compositions;
        }

        return view("onboarding.step{$step}", $viewData);
    }

    // Step 1: Nickname + Tanggal Lahir + Jenis Kelamin
    public function saveStep1(Request $request) {
        // If birth parts are submitted, merge into a single birth_date value
        if ($request->filled('birth_day') && $request->filled('birth_month') && $request->filled('birth_year')) {
            $day = (int) $request->input('birth_day');
            $month = (int) $request->input('birth_month');
            $year = (int) $request->input('birth_year');

            if (checkdate($month, $day, $year)) {
                $request->merge(['birth_date' => sprintf('%04d-%02d-%02d', $year, $month, $day)]);
            }
        }

        /** @var User $user */
        $user = Auth::user();

        $rules = [
            'nickname' => 'required|string|max:100',
            'gender'   => 'required|in:male,female',
            'city'     => 'required|string|max:100',
        ];

        if (blank($user->birth_date)) {
            $rules['birth_date'] = 'required|date|before:today';
        }

        $request->validate($rules);

        /** @var User $user */
$user = Auth::user();

        $update = [
            'nickname'        => $request->nickname,
            'birth_date'      => $request->birth_date,
            'gender'          => $request->gender,
            'city'            => $request->city,
            'onboarding_step' => 2,
        ];

        $user->update($update);

        return redirect()->route('onboarding.step', ['step' => 2]);
    }

    // Step 2: Info aplikasi + Wilayah
    public function saveStep2(Request $request) {
        // Combined preferences: allergens + medical needs
        $validator = Validator::make($request->all(), [
            'has_allergy' => 'required|in:yes,no',
            'allergens' => 'nullable|array',
            'allergens.*' => 'string|max:100',
            'custom_allergy' => 'nullable|string|max:100',
            'has_medical_need' => 'required|in:yes,no',
            'food_item' => 'nullable|required_if:has_medical_need,yes|string|max:255',
            'quantity' => 'nullable|required_if:has_medical_need,yes|integer|min:1',
            'unit' => 'nullable|required_if:has_medical_need,yes|string|max:50',
            'duration_type' => 'nullable|required_if:has_medical_need,yes|in:daily,weekly,monthly,yearly,forever',
        ]);

        $validator->after(function ($v) use ($request) {
            if ($request->input('has_allergy') === 'yes') {
                $allergenList = collect($request->input('allergens', []))->filter();
                if ($allergenList->isEmpty() && blank($request->input('custom_allergy'))) {
                    $v->errors()->add('allergens', 'Pilih minimal satu alergen atau isi Alergi Lain.');
                }
            }
        });

        $validator->validate();

        /** @var User $user */
        $user = Auth::user();

        // Allergies: save only when user chooses yes.
        $user->allergies()->delete();
        if ($request->input('has_allergy') === 'yes') {
            $allergens = collect($request->input('allergens', []))
                ->filter()
                ->map(fn ($allergen) => $this->normalizeCompositionLabel($allergen))
                ->filter()
                ->unique()
                ->values();

            foreach ($allergens as $allergen) {
                UserAllergy::create(['user_id' => $user->id, 'allergen' => $allergen]);
            }

            if ($request->filled('custom_allergy')) {
                UserAllergy::create(['user_id' => $user->id, 'allergen' => $this->normalizeCompositionLabel($request->custom_allergy)]);
            }
        }

        // Medical need: save only when user chooses yes.
        $user->medicalNeeds()->delete();
        if ($request->has_medical_need === 'yes') {
            UserMedicalNeed::create([
                'user_id' => $user->id,
                'food_item' => $request->food_item,
                'quantity' => $request->quantity,
                'unit' => $request->unit,
                'duration_type' => $request->duration_type,
                'start_date' => now()->toDateString(),
                'end_date' => null,
                'is_active' => true,
            ]);
        }

        // Mark progress
        $hasGuestHealthData = filled($user->height_cm)
            || filled($user->weight_kg)
            || filled($user->activity_level)
            || filled($user->bmi)
            || filled($user->daily_calorie_needs);

        if ($hasGuestHealthData) {
            $user->update(['onboarding_step' => 5, 'onboarding_completed' => true]);
            return redirect()->route('user.dashboard')->with('welcome', true);
        }

        $user->update(['onboarding_step' => 5]);
        return redirect()->route('onboarding.step', ['step' => 5]);
    }

    // Step 3: Alergi
    public function saveStep3(Request $request) {
        /** @var User $user */
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'has_allergy' => 'required|in:yes,no',
            'allergens' => 'nullable|array',
            'allergens.*' => 'string|max:100',
            'custom_allergy' => 'nullable|string|max:100',
        ]);

        $validator->after(function ($v) use ($request) {
            if ($request->input('has_allergy') === 'yes') {
                $allergenList = collect($request->input('allergens', []))->filter();
                if ($allergenList->isEmpty() && blank($request->input('custom_allergy'))) {
                    $v->errors()->add('allergens', 'Pilih minimal satu alergen atau isi Alergi Lain.');
                }
            }
        });

        $validator->validate();

        // Replace existing allergies with submitted values, or clear all when user has none.
        $user->allergies()->delete();

        if ($request->input('has_allergy') === 'yes') {
            $allergens = collect($request->input('allergens', []))
                ->filter()
                ->map(fn ($allergen) => $this->normalizeCompositionLabel($allergen))
                ->filter()
                ->unique()
                ->values();

            foreach ($allergens as $allergen) {
                UserAllergy::create(['user_id' => $user->id, 'allergen' => $allergen]);
            }

            if ($request->filled('custom_allergy')) {
                UserAllergy::create(['user_id' => $user->id, 'allergen' => $this->normalizeCompositionLabel($request->custom_allergy)]);
            }
        }

        $user->update(['onboarding_step' => 4]);
        return redirect()->route('onboarding.step', ['step' => 4]);
    }

    // Step 4: Kebutuhan medis khusus
    public function saveStep4(Request $request) {
        /** @var User $user */
        $user = Auth::user();
        $hasGuestHealthData = filled($user->height_cm)
            || filled($user->weight_kg)
            || filled($user->activity_level)
            || filled($user->bmi)
            || filled($user->daily_calorie_needs);

        $validator = Validator::make($request->all(), [
            'has_medical_need' => 'required|in:yes,no',
            'food_item' => 'nullable|string|max:255',
            'quantity' => 'nullable|integer|min:1',
            'unit' => 'nullable|string|max:50',
            'duration_type' => 'nullable|in:daily,weekly,monthly,yearly,forever',
        ]);

        $validator->after(function ($v) use ($request) {
            if ($request->input('has_medical_need') === 'yes') {
                if (blank($request->input('food_item'))) {
                    $v->errors()->add('food_item', 'Bahan Makanan wajib diisi.');
                }
                if (blank($request->input('quantity'))) {
                    $v->errors()->add('quantity', 'Kuantitas wajib diisi.');
                }
                if (blank($request->input('unit'))) {
                    $v->errors()->add('unit', 'Satuan wajib dipilih.');
                }
                if (blank($request->input('duration_type'))) {
                    $v->errors()->add('duration_type', 'Frekuensi wajib dipilih.');
                }
            }
        });

        $validator->validate();

        // Replace existing medical needs with the submitted one, or clear them if user has none.
        $user->medicalNeeds()->delete();

        if ($request->input('has_medical_need') === 'yes') {
            UserMedicalNeed::create([
                'user_id'       => $user->id,
                'food_item'     => $request->food_item,
                'quantity'      => $request->quantity,
                'unit'          => $request->unit,
                'duration_type' => $request->duration_type,
                'start_date'    => now()->toDateString(),
                'end_date'      => null,
                'is_active'     => true,
            ]);
        }

        if ($hasGuestHealthData) {
            $user->update([
                'onboarding_step' => 4,
                'onboarding_completed' => true,
            ]);

            return redirect()->route('user.dashboard')->with('welcome', true);
        }

        $user->update(['onboarding_step' => 5]);
        return redirect()->route('onboarding.step', ['step' => 5]);
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
            'Seafood' => ['seafood', 'sea food', 'mixed seafood'],
            'Santan' => ['santan', 'coconut milk', 'kelapa'],
            'Telur' => ['telur', 'egg', 'eggs'],
            'Udang' => ['udang', 'shrimp'],
            'Kepiting' => ['kepiting', 'crab'],
            'Cumi' => ['cumi', 'squid'],
            'Gandum' => ['gandum', 'gluten', 'wheat', 'terigu'],
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

    // Step 5: TB, BB → Hitung BMI & Kalori
    public function saveStep5(Request $request) {
        $request->validate([
            'age'            => 'required|integer|min:1|max:120',
            'gender'         => 'required|in:male,female',
            'height_cm'      => 'required|numeric|min:50|max:300',
            'weight_kg'      => 'required|numeric|min:10|max:500',
            'activity_level' => 'required|in:sedentary,light,moderate,active,very_active',
        ]);

        /** @var User $user */
$user = Auth::user();
        $age  = (int) $request->age;

        $bmi  = $this->calorieService->calculateBMI($request->weight_kg, $request->height_cm);
        $bmr  = $this->calorieService->calculateBMR($request->weight_kg, $request->height_cm, $age, $request->gender);
        $tdee = $this->calorieService->calculateTDEE($bmr, $request->activity_level);

        $birthDate = now()->startOfDay()->subYears($age);

        $user->update([
            'birth_date'          => $birthDate,
            'gender'              => $request->gender,
            'height_cm'           => $request->height_cm,
            'weight_kg'           => $request->weight_kg,
            'activity_level'      => $request->activity_level,
            'bmi'                 => $bmi,
            'daily_calorie_needs' => round($tdee),
            'onboarding_completed'=> true,
            'onboarding_step'     => 5,
        ]);

        // Buat reminder default
        MealReminder::insert([
            ['user_id'=>$user->id,'meal_type'=>'breakfast','reminder_time'=>'07:00:00','is_active'=>true,'created_at'=>now(),'updated_at'=>now()],
            ['user_id'=>$user->id,'meal_type'=>'lunch','reminder_time'=>'12:00:00','is_active'=>true,'created_at'=>now(),'updated_at'=>now()],
            ['user_id'=>$user->id,'meal_type'=>'dinner','reminder_time'=>'18:30:00','is_active'=>true,'created_at'=>now(),'updated_at'=>now()],
        ]);

        return redirect()->route('user.dashboard')->with('welcome', true);
    }
}