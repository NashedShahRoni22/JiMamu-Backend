<?php

namespace App\Http\Controllers\Api\Auth;

use App\Events\OtpGenerated;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\OtpVerify;
use App\Models\User;
use App\Services\Auth\MakeVerificationCodeService;
use App\Services\Files\FileService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct(public MakeVerificationCodeService $makeVerificationCodeService, protected FileService $fileService)
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
                'sender_type' => 'email',
                'otp_type' => 'account_verification',
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
        if(!$exitsUser){
            $user = User::create([
                'email' => $request->email,
                'status' => 1,
            ]);
            $exitsUser = $user;
            $user->assignRole('user');
        }
        $token = $exitsUser->createToken('auth_token')->plainTextToken;

        return sendResponse(true, 'OTP Verified successfully.', ["token" => $token, "status" => User::$statusName[$exitsUser?->status], 'role' => $exitsUser->getRoleNames()->toArray()]);
    }
    public function socialLogin(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        // if not have existing user then user will create
        if(!$user) {
            $fileName = $this->fileService->sliceFileUrl($user?->profile_image);
            $request->validate([
                'email' => 'required|string|email|unique:users,email',
                'user_type' => 'required|string|in:user,rider',
                'dob' => 'required|string',
                'gender' => 'required|string',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            ]);
            // if profile image not null
        }
        try {
            if (!$user){
                if ($request->hasFile('profile_image')) {
                    // store profile image using service class
                    $fileName = $this->fileService->uploadFile($request->file('profile_image'), 'user');
                }
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'profile_image' => $fileName,
                    'dob' => $request->dod,
                    'gender' => $request->gender,
                    'user_type' => User::$userType[$request?->user_type],
                    'status' => User::$status['active'],
                ]);
                $user->assignRole('user');
            }
            $token = $user->createToken('auth_token')->plainTextToken;
            return sendResponse(true, 'Login Successful.', ["token" => $token, "status" => User::$statusName[$user?->status], 'role' => $user->getRoleNames()->toArray()]);
        }catch (\Exception $e){
            return sendResponse(false, $e->getMessage(), null, 500);
        }
    }
}
