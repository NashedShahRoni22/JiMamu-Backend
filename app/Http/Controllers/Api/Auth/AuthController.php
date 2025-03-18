<?php

namespace App\Http\Controllers\Api\Auth;

use App\Events\OtpGenerated;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\OtpVerify;
use App\Models\User;
use App\Services\Auth\MakeVerificationCodeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct(public MakeVerificationCodeService $makeVerificationCodeService)
    {

    }
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
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'otp_code' => 'required|digits:4',
        ]);

       $otpCode = OtpVerify::where('email', $request->email)->where('otp_code', $request->otp_code)->first();

        if (!$otpCode || $otpCode->otp_code !== $request->otp_code || Carbon::now()->gt($otpCode->otp_expires_at)) {
            return sendResponse(false, 'Invalid or expired OTP.', null,401);
        }
        // clear otp
        $otpCode->delete();

        // email check
        $exitsUser = User::where('email', $request->email)->first();
        if($exitsUser){
             $exitsUser->update([
                'status' => 2,
            ]);
        }else{
            $user = User::create([
                'name' => 'test',
                'email' => 'akazad914@gmail.com',
                'status' => 1,
            ]);
            $exitsUser = $user;
        }
        $token = $exitsUser->createToken('auth_token')->plainTextToken;

        return sendResponse(true, 'OTP Verified successfully.', ["token" => $token, "status" => User::$statusName[$exitsUser?->status]]);
    }
}
