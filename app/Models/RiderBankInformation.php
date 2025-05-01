<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiderBankInformation extends Model
{
    public static $type = ['card' => 1, 'bank_account' => 2];
    static $REVIEW_STATUS = [1 => 'pending', 2 => 'accepted', 3 => 'rejected'];

    protected $fillable = ['user_id', 'name', 'account_number', 'cvc_code', 'expire_date', 'type', 'is_default_payment', 'review_status', 'remarks'];
}
