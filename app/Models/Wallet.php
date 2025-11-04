<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = ['user_id', 'balance'];

    public function walletHistory(){
        return $this->hasMany(WalletHistory::class, 'wallet_id', 'id');
    }
    public function walletHistories(){
        return $this->hasMany(WalletHistory::class, 'wallet_id', 'id');
    }
}
