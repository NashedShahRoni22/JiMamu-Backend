<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FareSetting extends Model
{
    protected $fillable = ['minimum_fare', 'distance_rete', 'platform_charge', 'created_at', 'updated_at'];
}
