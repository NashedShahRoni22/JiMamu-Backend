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
        Schema::create('user_riders', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 30);
            $table->string('document_type');
            $table->string('document_number')->nullable();
            $table->string('document');
            $table->tinyInteger('review_status')->default(1); // pending, accepted, cancel
            $table->string('remarks')->nullable(); // if cancel with the reason the request
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_riders');
    }
};
