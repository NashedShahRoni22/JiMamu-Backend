<?php

namespace App\Http\Controllers\Api\Auth;

use App\Events\OtpGenerated;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Models\OtpVerify;
use App\Models\User;
use App\Services\Auth\MakeVerificationCodeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct(public MakeVerificationCodeService $makeVerificationCodeService)
    {

    }
    public function sendEmailOtp($email)
    {
        try {
             $otp = OtpVerify::create([
                'email' => $email,
                'otp_code' => rand(1000, 9999),
                'otp_expires_at' => now()->addMinutes(3),
                'otp_type' => "phone_number",
            ]);
            event(new OtpGenerated(1251)); // Dispatch event
            return sendResponse(true, 'OTP Send successfully.');
        }catch (CustomException $e){
            return $e->getMessage();
        }




//        $user = User::create([
//            'name' => $request->name,
//            'email' => $request->email,
//            'password' => bcrypt($request->password),
//            'verification_code' => $this->makeVerificationCodeService->makeEmailVerificationCode(),
//            'email_verification_token' => Str::random(40),
//        ]);

//        event(new UserRegisteredEvent($otp)); // Dispatch event
//
//        return sendResponse(true, 'User registered successfully.', [
//            'token' => $user->createToken('auth_token')->plainTextToken,
//            'user' => $user
//        ]);

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

        // Clear OTP after successful verification

//        $otpCode->otp_code = null;
//        $otpCode->otp_expires_at = null;
//        $otpCode->verified_at = now();
//        $otpCode->save();
//        return 'ok';
       // $otpCode->delete();

        // Generate Sanctum token
        $user = User::create([
            'name' => 'test',
            'email' => $otpCode->email,
            'status' => User::$status['active'],
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;

        return sendResponse(true, 'OTP Verified successfully.', ["token" => $token]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        try {
            $user = User::where('email', $request->email)->firstOrFail();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw new CustomException('Password Not Matched',  404);
            }
            return sendResponse(true, 'Login Successfully', ['token' => $user->createToken('auth_token')->plainTextToken]);
        }catch (\Exception $exception){
            return sendResponse(false, 'Something Went Wrong', $exception->getCode());
        }

    }
}
