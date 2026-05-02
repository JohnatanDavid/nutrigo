<?php
namespace App\Services;

use App\Models\Food;
use App\Models\User;
use Illuminate\Support\Collection;

class AllergyFilterService {

    public function filterFoods(Collection $foods, User $user): Collection {
        $allergens = $user->allergies->pluck('allergen')->toArray();
        if (empty($allergens)) return $foods;

        return $foods->filter(function (Food $food) use ($allergens) {
            $composition = strtolower($food->composition ?? '');
            foreach ($allergens as $allergen) {
                if (str_contains($composition, strtolower($allergen))) {
                    return false;
                }
            }
            return true;
        });
    }

    public function checkFoodSafety(Food $food, User $user): array {
        $allergens   = $user->allergies->pluck('allergen')->toArray();
        $composition = strtolower($food->composition ?? '');
        $found       = [];

        foreach ($allergens as $allergen) {
            if (str_contains($composition, strtolower($allergen))) {
                $found[] = $allergen;
            }
        }

        return [
            'safe'     => empty($found),
            'allergens'=> $found,
        ];
    }
}