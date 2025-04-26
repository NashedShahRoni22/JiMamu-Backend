<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
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
    public function receiver_information(){
        return $this->hasOne(ReceiverInformation::class, 'order_id', 'id');
    }
}
