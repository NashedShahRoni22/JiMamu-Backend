<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class UserRider extends Model
{
    static $REVIEW_STATUS = [1 => 'pending', 2 => 'approved', 3 => 'rejected'];
    static $REVIEW_STATUS_NAME = ['pending' => 1, 'approved' => 2, 'rejected' => 3];
    protected $fillable = ['user_id', 'document_type', 'document_number', 'document', 'review_status', 'remarks', 'expire_date'];


    public function document() : Attribute
    {
        return Attribute::make(
            get: fn($value) => collect(json_decode($value, true))
                ->map(fn($doc) => $doc != null ?asset('storage/' . $doc) : null)
                ->all(),
        );
    }
}
