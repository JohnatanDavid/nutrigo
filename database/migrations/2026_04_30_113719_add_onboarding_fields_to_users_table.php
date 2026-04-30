<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nickname')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->float('height_cm')->nullable();
            $table->float('weight_kg')->nullable();
            $table->float('bmi')->nullable();
            $table->float('daily_calorie_needs')->nullable();
            $table->enum('activity_level', ['sedentary','light','moderate','active','very_active'])
                ->default('moderate');
            $table->boolean('is_admin')->default(false);
            $table->boolean('onboarding_completed')->default(false);
            $table->integer('onboarding_step')->default(0);
            $table->timestamp('last_activity')->nullable();
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nickname','birth_date','gender','province','city',
                'height_cm','weight_kg','bmi','daily_calorie_needs',
                'activity_level','is_admin','onboarding_completed',
                'onboarding_step','last_activity'
            ]);
        });
    }
};