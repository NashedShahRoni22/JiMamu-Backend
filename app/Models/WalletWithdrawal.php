<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletWithdrawal extends Model
{
    protected $fillable = ['user_id', 'wallet_id', 'amount', 'status'];
}
