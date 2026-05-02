<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserAllergy extends Model {
    protected $fillable = ['user_id','allergen'];
    public function user() { return $this->belongsTo(User::class); }
}