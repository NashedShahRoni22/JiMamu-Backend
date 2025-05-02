<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bid extends Model
{
    use SoftDeletes;

    static $STATUS = ['pending' => 1, 'accepted' => 2, 'rejected' => 3];
    static $STATUS_NAME = [1 => 'pending', 2 => 'accepted', 3 => 'rejected'];

    protected $fillable = ['order_id', 'user_id', 'order_attempt_id', 'bid_amount',  'status'];

    // scope accepted query
    public function scopeAcceptedBid($query){
        return $query->where('status', Bid::$STATUS['accepted']);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function rider()
    {
        return $this->belongsTo(User::class, 'rider_id', 'id');
    }
}
