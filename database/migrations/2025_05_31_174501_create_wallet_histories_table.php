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
        Schema::create('wallet_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id');
            $table->foreignId('user_id');
            $table->foreignId('order_id')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->tinyInteger('purpose_of_transaction')->default(1); // customer_order_cancel, rider_order_cancel, order_completed, withdrawal
            $table->tinyInteger('transaction_type'); // debit, credit
            $table->tinyInteger('status')->default(1); // pending, processing, approved, cancelled
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_histories');
    }
};
