<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FoodHistory extends Model {
    protected $fillable = [
        'user_id','food_id','meal_type',
        'calories_consumed','consumed_date','consumed_time'
    ];
    protected $casts = ['consumed_date' => 'date'];
    public function user() { return $this->belongsTo(User::class); }
    public function food() { return $this->belongsTo(Food::class); }
}