<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CalorieCalculatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller {

    public function __construct(private CalorieCalculatorService $calorieService) {}

    public function showRegistrationForm() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|min:8|confirmed',
            'height_cm'             => 'nullable|numeric|min:50|max:300',
            'weight_kg'             => 'nullable|numeric|min:10|max:500',
            'age'                   => 'nullable|integer|min:1|max:120',
            'gender'                => 'nullable|in:male,female',
            'activity_level'        => 'nullable|in:sedentary,light,moderate,active,very_active',
            'bmi'                   => 'nullable|numeric',
            'bmi_category'          => 'nullable|string|max:50',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Password dan konfirmasi password harus sama.',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
            'password_confirmation.same' => 'Konfirmasi password tidak sesuai.',
        ]);

        $birthDate = null;
        $bmi = $request->filled('bmi') ? (float) $request->bmi : null;
        $dailyCalories = null;

        if ($request->filled('age')) {
            $birthDate = now()->startOfDay()->subYears((int) $request->age);
        }

        if (!$bmi && $request->filled('height_cm') && $request->filled('weight_kg')) {
            $bmi = $this->calorieService->calculateBMI($request->weight_kg, $request->height_cm);
        }

        if ($request->filled('height_cm') && $request->filled('weight_kg') && $request->filled('age') && $request->filled('gender') && $request->filled('activity_level')) {
            $bmr = $this->calorieService->calculateBMR($request->weight_kg, $request->height_cm, (int) $request->age, $request->gender);
            $dailyCalories = round($this->calorieService->calculateTDEE($bmr, $request->activity_level));
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $this->checkIfAdmin($request->email),
            'height_cm'           => $request->height_cm,
            'weight_kg'           => $request->weight_kg,
            'province'            => $request->province,
            'bmi'                 => $bmi,
            'daily_calorie_needs' => $dailyCalories,
            'activity_level'      => $request->input('activity_level', 'moderate'),
            'gender'              => $request->gender,
            'birth_date'          => $birthDate,
            'onboarding_step'     => 1,
            'onboarding_completed'=> false,
        ]);

        Auth::login($user);

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Persist any guest draft fields that may have been submitted as hidden inputs
        $update = [];
        foreach (['height_cm','weight_kg','activity_level','province','bmi','daily_calorie_needs'] as $f) {
            if ($request->filled($f)) {
                $update[$f] = $request->input($f);
            }
        }

        if ($request->filled('age')) {
            $update['birth_date'] = now()->startOfDay()->subYears((int) $request->age);
        }

        if (!empty($update)) {
            $user->update($update);
        }

        $hasGuestHealthData = filled($user->height_cm)
            || filled($user->weight_kg)
            || filled($user->activity_level)
            || filled($user->bmi)
            || filled($user->daily_calorie_needs);

        if ($hasGuestHealthData) {
            $user->update(['onboarding_step' => 2]);

            return redirect()->route('onboarding.step', ['step' => 1]);
        }

        return redirect()->route('onboarding.step', ['step' => 1]);
    }

    private function checkIfAdmin(string $email): bool {
        $adminEmails = array_map('trim', explode(',', config('nutrigo.admin_emails', '')));
        return in_array($email, $adminEmails);
    }
}