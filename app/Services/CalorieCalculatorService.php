<?php
namespace App\Services;

class CalorieCalculatorService {

    /**
     * Hitung BMR menggunakan Mifflin-St Jeor Equation
     */
    public function calculateBMR(float $weight, float $height, int $age, string $gender): float {
        if ($gender === 'male') {
            return (10 * $weight) + (6.25 * $height) - (5 * $age) + 5;
        }
        return (10 * $weight) + (6.25 * $height) - (5 * $age) - 161;
    }

    /**
     * Hitung TDEE (Total Daily Energy Expenditure)
     */
    public function calculateTDEE(float $bmr, string $activityLevel): float {
        $multipliers = config('nutrigo.activity_multipliers');
        return $bmr * ($multipliers[$activityLevel] ?? 1.55);
    }

    /**
     * Hitung BMI
     */
    public function calculateBMI(float $weight, float $height): float {
        $heightM = $height / 100;
        return round($weight / ($heightM * $heightM), 1);
    }

    /**
     * Kategori BMI
     */
    public function getBMICategory(float $bmi): string {
        return match(true) {
            $bmi < 18.5 => 'Kurus (Underweight)',
            $bmi < 25.0 => 'Normal',
            $bmi < 30.0 => 'Gemuk (Overweight)',
            default     => 'Obesitas',
        };
    }

    /**
     * Kalori per waktu makan
     */
    public function getMealCalories(float $dailyCalories): array {
        $dist = config('nutrigo.meal_distribution');
        return [
            'breakfast' => round($dailyCalories * $dist['breakfast']),
            'lunch'     => round($dailyCalories * $dist['lunch']),
            'dinner'    => round($dailyCalories * $dist['dinner']),
        ];
    }
}