<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MealReminder extends Model {
    protected $fillable = ['user_id','meal_type','reminder_time','is_active'];
    protected $casts = ['is_active' => 'boolean'];
    public function user() { return $this->belongsTo(User::class); }
}