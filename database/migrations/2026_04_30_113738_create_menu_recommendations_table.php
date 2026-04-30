<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('menu_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('recommendation_date');
            $table->foreignId('breakfast_id')->nullable()->constrained('foods');
            $table->foreignId('lunch_id')->nullable()->constrained('foods');
            $table->foreignId('dinner_id')->nullable()->constrained('foods');
            $table->float('total_calories')->default(0);
            $table->boolean('is_saved')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('menu_recommendations'); }
};