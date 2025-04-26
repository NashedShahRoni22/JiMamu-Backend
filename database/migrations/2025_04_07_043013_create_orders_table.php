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
            $table->foreignId('package_id');
            $table->string('pickup_latitude');
            $table->string('pickup_longitude');
            $table->string('drop_latitude');
            $table->string('drop_longitude');
            $table->float('weight');
            $table->float('price');
            $table->float('pickup_radius')->default(1.0); // in kilometers
            $table->tinyInteger('status')->default(1); // pending, assigned, picked_up, delivered, canceled
            $table->tinyInteger('payment_status')->default(1); // unpaid, paid
            $table->string('tracking_code')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('rider_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
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
