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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rider_id');
            $table->foreignId('customer_id');
            $table->foreignId('order_request_id');
            $table->float('amount');
            $table->tinyInteger('status')->default(1); // pending, assigned, picked_up, delivered, canceled
            $table->tinyInteger('payment_status')->default(1); // unpaid, paid
            $table->string('tracking_code')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('rider_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_request_id')->references('id')->on('order_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
