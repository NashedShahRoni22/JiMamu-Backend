<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderRequest extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'package_id',
        'pickup_latitude',
        'pickup_longitude',
        'drop_latitude',
        'drop_longitude',
        'weight',
        'price',
        'status',
    ];
}
