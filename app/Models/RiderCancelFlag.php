<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiderCancelFlag extends Model
{
    protected $fillable = [
        'rider_id',
        'order_id',
        'reason',
    ];

    public function rider()
    {
        return $this->belongsTo(User::class, 'rider_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
