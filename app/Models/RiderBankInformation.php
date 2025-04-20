<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiderBankInformation extends Model
{
    public static $type = ['card' => 1, 'bank_account' => 2];
    protected $fillable = ['user_id', 'name', 'account_number', 'cvc_code', 'expiry_date', 'type', 'is_default_payment'];
}
