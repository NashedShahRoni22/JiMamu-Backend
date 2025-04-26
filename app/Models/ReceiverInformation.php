<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiverInformation extends Model
{

    protected $fillable = [
        'order_id',
        'receiver_name',
        'receiver_phone',
        'remarks',
    ];
}
