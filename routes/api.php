<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\ProfileUpdateController;
use Laravel\Socialite\Facades\Socialite;

Route::middleware(['json.response'])->prefix('/v1')->group(function() {
    Route::get('/send/email/otp/{email}', [AuthController::class, 'sendEmailOtp']);
    Route::post('/email/otp/verify', [AuthController::class, 'verifyOtp']);

    Route::get('/auth/google', function () {
        return Socialite::driver('google')->stateless()->redirect();
    });
    Route::get('/auth/google/callback', function (Request $request) {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = \App\Models\User::updateOrCreate([
                'email' => $googleUser->getEmail(),
            ], [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
            ]);

            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json(['token' => $token, 'user' => $user]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    });

    Route::middleware(['auth:sanctum'])->group(function () {

        Route::prefix('/user')->group(function () {

            Route::prefix('/profile')->group(function () {
                Route::get('/', [ProfileUpdateController::class, 'UserProfileShow']); // Get user profile
                Route::put('/update', [ProfileUpdateController::class, 'userProfileUpdate']); // Update user profile
            });
        });
    });




});
