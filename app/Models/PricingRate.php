<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingRate extends Model
{
    protected $fillable = ['base_fare', 'platform_charge', 'type'];

    static $STATUS = ['national' => 1, 'international' => 2];

    protected $casts = [
        'base_fare'       => 'decimal:2',
        'platform_charge' => 'decimal:2',
        'type'            => 'integer',
    ];
 
    /**
     * Get the human-readable label for the type.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            1 => 'National',
            2 => 'International',
            default => 'Unknown',
        };
    }
}
