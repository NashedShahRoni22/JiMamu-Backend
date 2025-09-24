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
        Schema::create('weight_rules', function (Blueprint $table) {
            $table->id();
            $table->decimal('min_weight_kg', 8, 2)->default(0);  // e.g., 0
            $table->decimal('max_weight_kg', 8, 2)->nullable();  // e.g., 1, null = unlimited
            $table->decimal('cost_per_kg', 10, 2)->default(0); // e.g., 10, 8, 6
            $table->boolean('is_base_price')->default(false); // true = just base price, no per kg
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weight_roles');
    }
};
