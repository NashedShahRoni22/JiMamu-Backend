<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankInformation extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'account_number',
        'institution_number',
        'transit_number',
        'is_default_payment',
        'type',
        'review_status',
        'remarks',
        'bank_document',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_default_payment' => 'boolean',
        'type' => 'integer',
        'review_status' => 'integer',
    ];
    public function bankDocument() : Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? asset('storage/').'/'.$value : null
        );
    }


    /**
     * Get the user that owns the bank information.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
