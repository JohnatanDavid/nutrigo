<?php
namespace App\Models;

use App\Models\MealReminder;
use App\Models\Notification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable {
    
    use HasFactory, Notifiable;

    protected $fillable = [
        'name','email','password','nickname','birth_date','gender',
        'province','city','height_cm','weight_kg','bmi',
        'daily_calorie_needs','activity_level','is_admin',
        'onboarding_completed','onboarding_step','last_activity',
    ];

    protected $hidden = ['password','remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date'        => 'date',
        'is_admin'          => 'boolean',
        'onboarding_completed' => 'boolean',
        'password'          => 'hashed',
    ];

    // ─── Admin check ───────────────────────────────────────────
    public function isAdmin(): bool {
        $adminEmails = array_map('trim', explode(',', config('nutrigo.admin_emails', '')));
        return in_array($this->email, $adminEmails) || $this->is_admin;
    }

    public function getAge(): int {
        return $this->birth_date ? $this->birth_date->age : 0;
    }

    // ─── Relations ─────────────────────────────────────────────
    public function allergies(): HasMany      { return $this->hasMany(UserAllergy::class); }
    public function medicalNeeds(): HasMany   { return $this->hasMany(UserMedicalNeed::class); }
    public function foodHistories(): HasMany  { return $this->hasMany(FoodHistory::class); }
    public function recommendations(): HasMany{ return $this->hasMany(MenuRecommendation::class); }
    public function articles(): HasMany       { return $this->hasMany(Article::class, 'author_id'); }

    public function reminders(): HasMany
    {
        return $this->hasMany(MealReminder::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }
}
