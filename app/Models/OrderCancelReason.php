<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCancelReason extends Model
{
    // Keep existing table until migration/table is updated
    protected $table = 'order_cancel_reasons';

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
