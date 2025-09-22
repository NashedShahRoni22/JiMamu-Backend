<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('distance_zones', function (Blueprint $table) {
            $table->id();
            $table->decimal('min_distance_km', 8, 2)->default(0);   // e.g., 0
            $table->decimal('max_distance_km', 8, 2)->nullable();   // e.g., 50, null = unlimited
            $table->decimal('base_fare', 10, 2)->default(0);        // base fare for the zone
            $table->decimal('platform_charge', 10, 2)->default(0);
            $table->decimal('per_km_rate', 10, 2)->default(0);      // rate per km (optional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distance_zones');
    }
};
