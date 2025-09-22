<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeightRule extends Model
{
    protected $fillable = ['min_weight_kg', 'max_weight_kg', 'cost_per_kg', 'is_base_price'];
}
