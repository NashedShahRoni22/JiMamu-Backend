<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'name',
        'sort_description',
        'icon',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    /**
     * Get the human-readable label for the status.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            1 => 'Active',
            0 => 'Inactive',
            default => 'Unknown',
        };
    }
}
