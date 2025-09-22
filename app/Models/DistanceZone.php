<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistanceZone extends Model
{
    protected $fillable = ['min_distance_km', 'max_distance_km', 'base_fare', 'per_km_rate'];
}
