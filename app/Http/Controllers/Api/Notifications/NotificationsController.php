<?php

namespace App\Http\Controllers\Api\Notifications;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\OtpVerify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NotificationsController extends Controller
{
    public function sendEmailOtp(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
        ]);
        try {
            $otp = OtpVerify::create([
                'email' => $request->email,
                'otp_code' => rand(1000, 9999),
                'otp_expires_at' => now()->addMinutes(3),
                'otp_type' => "phone_number",
            ]);
            // event(new OtpGenerated(1251)); // Dispatch event
            Mail::to($request->email)->send(new OtpMail($otp->otp_code));

            return sendResponse(true, 'OTP Send successfully.');
        }catch (CustomException $e){
            return $e->getMessage();
        }
    }
}
