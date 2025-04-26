<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SenderInformation extends Model
{
    protected $fillable = [
        'order_id',
        'sender_name',
        'sender_phone',
        'remarks',
    ];
}
