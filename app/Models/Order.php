<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    //only using storing data, because from fronted give name
    static $ORDER_STATUS  = ['pending' => 1, 'confirmed' => 2, 'picked' => 3, 'shipping' => 4, 'delivered' => 5, 'cancelled' => 6 ];

    static $ORDER_STATUS_NAME  = [ 1 => 'pending', 2 => 'confirmed', 3 => 'picked', 4 => 'shipping', 5 => 'delivered', 6 => 'cancelled' ];
    static $ORDER_TYPE = ['national' => 1, 'international' => 2];

    static  $PAYMENT_STATUS = ['unpaid' => 1, 'paid' => 2];
    static  $PAYMENT_STATUS_NAME = [1 => 'unpaid', 2 => 'paid' ];

    // goods weight type
    static $WEIGHT_TYPE=['kg' => 1, 'lbs' => 2];
    static  $WEIGHT_TYPE_NAME = [1 => 'kg', 2 => 'lbs'];

    //use SoftDeletes;
    protected $fillable = [
        'order_unique_id',
        'rider_id',
        'customer_id',
        'package_id',
        'order_type',
        'pickup_latitude',
        'pickup_longitude',
        'drop_latitude',
        'drop_longitude',
        'weight',
        'weight_type',
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
    public function orderDestination(){
       return $this->hasOne(OrderDestination::class, 'order_id', 'id');
    }
    public function rider(){
        return $this->belongsTo(User::class, 'rider_id', 'id');
    }
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }
    public function riderCancelFlags(){
        return $this->hasMany(RiderCancelFlag::class, 'order_id', 'id');
    }

}
