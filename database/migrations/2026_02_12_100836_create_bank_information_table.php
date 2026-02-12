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
        Schema::create('bank_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('name');
            $table->string('account_number');
            $table->string('institution_number', 10)->nullable();
            $table->string('transit_number', 10)->nullable();
            $table->boolean('is_default_payment')->default(false);
            $table->tinyInteger('type')->default(0); // rider/user
            $table->tinyInteger('review_status')->default(1); // pending, accepted, cancel
            $table->string('remarks')->nullable(); // if cancel with the reason the request
            $table->string('bank_document')->nullable(); // image/pdf

            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_information');
    }
};
