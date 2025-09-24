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
        Schema::create('customer_card_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('card_type'); // Visa, MasterCard, Others
            $table->string('card_number'); // Store securely! (See note below)
            $table->string('expire_date'); // e.g., 12/26
            $table->string('cvc_code'); // Store securely! (See note below)
            $table->boolean('is_default_payment')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_card_information');
    }
};
