<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('foods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('calories');
            $table->float('proteins')->default(0);
            $table->float('fat')->default(0);
            $table->float('carbohydrate')->default(0);
            $table->text('composition')->nullable();
            $table->string('origin')->nullable(); // provinsi asal
            $table->string('region')->nullable(); // pulau/wilayah besar
            $table->enum('meal_type', ['breakfast','lunch','dinner','snack'])->default('lunch');
            $table->string('image_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('foods'); }
};