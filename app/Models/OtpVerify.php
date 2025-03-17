<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpVerify extends Model
{
    protected $fillable = ['email', 'phone_number', 'otp_code', 'otp_expires_at', 'verified_at', 'otp_type'];
}
