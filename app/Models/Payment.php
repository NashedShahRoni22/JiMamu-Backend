<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['order_id', 'order_attempt_id', 'amount', 'payment_method', 'status'];
}
