<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBankInformation extends Model
{
    protected $fillable = ['user_id', 'name', 'account_number', 'cvc_code', 'expiry_date', 'type'];

}
