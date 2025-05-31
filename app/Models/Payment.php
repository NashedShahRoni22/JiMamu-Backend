<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    static $PAYMENT_STATUS = ['paid' => 1, 'unpaid' => 2, 'cancelled' => 3];
    protected $fillable = ['order_id', 'order_attempt_id', 'amount', 'payment_method', 'status'];
}
