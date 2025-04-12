<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRider extends Model
{
    protected $fillable = ['user_id', 'document_type', 'document'];
}
