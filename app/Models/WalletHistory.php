<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletHistory extends Model
{
    static $PURPOSE_OF_TRANSACTION = ['customer_order_cancel' => 1, 'rider_order_cancel' => 2, 'order_completed' => 3, 'withdrawal' => 4];
    static $TRANSACTION_TYPE = ['debit' => 1, 'credit' => 2];
    static $STATUS = ['pending' => 1, 'processing' => 2, 'approved' => 3, 'cancelled' => 4];
    protected $fillable = [
        'wallet_id',
        'user_id',
        'order_id',
        'amount',
        'purpose_of_transaction',
        'transaction_type',
        'status',
    ];
}
