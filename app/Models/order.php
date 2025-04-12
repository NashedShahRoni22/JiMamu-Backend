<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class order extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'rider_id',
        'customer_id',
        'order_request_id',
        'amount',
        'status',
        'payment_status',
        'tracking_code',
    ];
}
