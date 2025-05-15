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
        Schema::create('order_destinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->string('area')->nullable();
            $table->string('address');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_destinations');
    }
};
