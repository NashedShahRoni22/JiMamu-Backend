<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletHistory extends Model
{
    static $PURPOSE_OF_TRANSACTION = ['customer_order_cancel' => 1, 'rider_order_cancel' => 2, 'order_completed' => 3, 'withdrawal' => 4, 'customer_order_paid' => 5];
    static $TRANSACTION_TYPE = ['debit' => 1, 'credit' => 2];
    static $STATUS = ['pending' => 1, 'processing' => 2, 'approved' => 3, 'cancelled' => 4];
    static $PURPOSE_OF_TRANSACTION_NAME = [1 => 'Order Cancel', 2 => 'Rider Order Cancel', 3 => 'Order Completed', 4 => 'Withdrawal', 5 => 'Order Paid'];
    static $TRANSACTION_TYPE_NAME = [1 => 'debit', 2 => 'credit'];
    static $STATUS_NAME = [1 => 'pending', 2 => 'processing', 3 => 'approved', 4 => 'cancelled'];
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
