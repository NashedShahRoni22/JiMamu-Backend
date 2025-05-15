<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDestination extends Model
{

    protected $fillable = ['order_id', 'country', 'state', 'city', 'postal_code', 'address', 'order_type'];
}
