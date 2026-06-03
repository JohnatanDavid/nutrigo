<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Food extends Model {
    use HasFactory;

    protected $table = 'foods';

    protected $fillable = [
        'name','calories','proteins','fat','carbohydrate',
        'composition','origin','food_category','is_national','region','meal_type','image_url','is_active'
    ];

    protected $casts = ['is_active' => 'boolean', 'is_national' => 'boolean'];

    // Filter berdasarkan wilayah
    public function scopeByRegion($query, string $region) {
        return $query->where(function ($q) use ($region) {
            $q->where('origin', 'like', "%{$region}%")
              ->orWhere('region', $region)
              ->orWhere('is_national', true);
        });
    }

    public function scopeEligibleForProvince($query, ?string $province) {
        if (!$province) {
            return $query->where(function ($q) {
                $q->where('is_national', true)->orWhere('region', 'Nasional');
            });
        }

        return $query->where(function ($q) use ($province) {
            $q->where('origin', $province)
              ->orWhere('region', 'Nasional')
              ->orWhere('is_national', true);
        });
    }

    // Filter exclude alergen
    public function scopeExcludeAllergens($query, array $allergens) {
        foreach ($allergens as $allergen) {
            $query->where('composition', 'not like', "%{$allergen}%");
        }
        return $query;
    }

    public function scopeForMealType($query, string $type) {
        return $query->where('meal_type', $type);
    }

    public function histories() { return $this->hasMany(FoodHistory::class); }
}