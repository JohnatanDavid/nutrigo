<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserMedicalNeed extends Model {
    protected $fillable = [
        'user_id','food_item','quantity','unit',
        'duration_type','start_date','end_date','is_active'
    ];
    protected $casts = ['is_active' => 'boolean', 'start_date' => 'date', 'end_date' => 'date'];
    public function user() { return $this->belongsTo(User::class); }
}