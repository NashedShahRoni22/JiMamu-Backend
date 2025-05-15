<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAttempt extends Model
{
    static $ORDER_STATUS  = ['pending' => 1, 'confirmed' => 2, 'picked' => 3, 'shipping' => 4, 'delivered' => 5, 'cancelled' => 6];

    static $ORDER_STATUS_NAME  = [1 => 'pending', 2 => 'confirmed', 3 => 'picked', 4 => 'shipping', 5 => 'delivered', 6 => 'cancelled'];

    protected $fillable = ['order_id', 'fare', 'parcel_estimate_price', 'order_tracking_number', 'payment_status', 'status'];

    // all bids get under a order attempts
    public function bids(){
        return $this->hasMany(Bid::class, 'order_attempt_id', 'id');
    }
    public function bid(){
        return $this->hasOne(Bid::class, 'order_attempt_id', 'id');
    }
    public function acceptedBid()
    {
        return $this->hasOne(Bid::class, 'order_attempt_id', 'id')->where('status', 2);
    }
    public function order(){
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
