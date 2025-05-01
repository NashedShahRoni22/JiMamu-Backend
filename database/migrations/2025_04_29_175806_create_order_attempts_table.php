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
        Schema::create('order_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->tinyInteger('status')->default(1); // pending, confirmed, picked_up, delivered, canceled
            $table->string('order_tracking_number');
            $table->float('fare');
            $table->tinyInteger('payment_status')->default(1); // unpaid, paid, cancel
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_attempts');
    }
};
