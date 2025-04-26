<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bid extends Model
{
    use SoftDeletes;
    protected $fillable = ['order_id', 'user_id', 'bid_amount'];

    public function user()
    {
        return $this->belongsTo(User::class, 'rider_id', 'id');
    }
    public function rider()
    {
        return $this->belongsTo(User::class, 'rider_id', 'id');
    }
}
