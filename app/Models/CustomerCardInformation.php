<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerCardInformation extends Model
{

    protected $fillable = [
        'user_id',
        'card_type',
        'card_number',
        'expire_date',
        'cvc_code',
        'is_default_payment',
    ];
}
