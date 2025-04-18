<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class UserRider extends Model
{
    protected $fillable = ['user_id', 'document_type', 'document_number', 'document'];


    public function document() : Attribute
    {
        return Attribute::make(
            get: fn($value) => collect(json_decode($value, true))
                ->map(fn($doc) => asset('storage/' . $doc))
                ->all(),
        );
    }
}
