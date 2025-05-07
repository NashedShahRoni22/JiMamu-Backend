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
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->foreignId('order_attempt_id');
            $table->foreignId('user_id'); // rider id
            $table->float('bid_amount');
            $table->tinyInteger('status')->default(1); // pending, accepted, rejected
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('order_attempt_id')->references('id')->on('order_attempts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')  ;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bids');
    }
};
