<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('foods', function (Blueprint $table) {
            $table->string('food_category')->nullable()->after('composition');
            $table->boolean('is_national')->default(false)->after('food_category');
        });
    }

    public function down(): void
    {
        Schema::table('foods', function (Blueprint $table) {
            $table->dropColumn(['food_category', 'is_national']);
        });
    }
};
