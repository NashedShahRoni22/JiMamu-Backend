<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPayment extends Model
{
    protected $fillable = [
        'order_id',
        'customer_id',
        'amount',
        'status',
        'transaction_type',
        'payment_method',
        'payment_reference',
        'currency',
    ];
}
