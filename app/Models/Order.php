<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    //only using storing data, because from fronted give name
    static $ORDER_STATUS  = ['pending' => 1, 'confirmed' => 2, 'picked' => 3, 'shipping' => 4, 'delivered' => 5, 'cancelled' => 6];

    static $ORDER_STATUS_NAME  = [1 => 'pending', 'confirmed' => 2, 'picked' => 3, 'shipping' => 4, 'delivered' => 5, 'cancelled' => 6];
    use SoftDeletes;
    protected $fillable = [
        'order_unique_id',
        'rider_id',
        'customer_id',
        'package_id',
        'pickup_latitude',
        'pickup_longitude',
        'drop_latitude',
        'drop_longitude',
        'weight',
        'fare',
        'pickup_radius',
        'status',
        'payment_status',
        'tracking_code',
    ];
    public function bids(){
        return $this->hasMany(Bid::class, 'order_id', 'id');
    }
    // single bid get
    public function bid(){
        return $this->hasOne(Bid::class, 'order_id', 'id');
    }
    public function receiverInformation(){
        return $this->hasOne(ReceiverInformation::class, 'order_id', 'id');
    }
}
