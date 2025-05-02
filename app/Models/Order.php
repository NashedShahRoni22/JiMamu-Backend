<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    //only using storing data, because from fronted give name
    static $ORDER_STATUS  = ['pending' => 1, 'confirmed' => 2, 'picked' => 3, 'shipping' => 4, 'delivered' => 5, 'cancelled' => 6];

    static $ORDER_STATUS_NAME  = [1 => 'pending', 2 => 'confirmed', 3 => 'picked', 4 => 'shipping', 5 => 'delivered', 6 => 'cancelled'];

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
        'pickup_radius',
        'status',
    ];
    public function orderAttempts(){
        return $this->hasMany(OrderAttempt::class, 'order_id', 'id');
    }
    public function orderAttempt(){
        return $this->hasOne(OrderAttempt::class, 'order_id', 'id');
    }
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
    public function senderInformation()
    {
        return $this->hasOne(SenderInformation::class, 'order_id', 'id');
    }
    public function customer(){
        return $this->belongsTo(User::class,  'customer_id', 'id');
    }

}
