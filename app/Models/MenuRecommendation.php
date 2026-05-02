<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MenuRecommendation extends Model {
    protected $fillable = [
        'user_id','recommendation_date',
        'breakfast_id','lunch_id','dinner_id','total_calories','is_saved'
    ];
    protected $casts = ['recommendation_date' => 'date', 'is_saved' => 'boolean'];
    public function user()      { return $this->belongsTo(User::class); }
    public function breakfast() { return $this->belongsTo(Food::class, 'breakfast_id'); }
    public function lunch()     { return $this->belongsTo(Food::class, 'lunch_id'); }
    public function dinner()    { return $this->belongsTo(Food::class, 'dinner_id'); }
}