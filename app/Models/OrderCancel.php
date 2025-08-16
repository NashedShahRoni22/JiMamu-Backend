<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCancel extends Model
{
    protected $fillable = ['order_id', 'customer_id', 'rider_id', 'status', 'reason'];
}
