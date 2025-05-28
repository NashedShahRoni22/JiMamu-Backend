<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\ProfileUpdateController;
use App\Http\Controllers\Api\Order\OrderRequestController;
use App\Http\Controllers\Api\Rider\RiderLocationController;
use App\Http\Controllers\Api\Rider\BidsController;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Api\Rider\MyDeliveryController;
use App\Http\Controllers\Api\StripePaymentController;

Route::middleware(['json.response'])->prefix('/v1')->group(function() {
    Route::post('/send/email/otp', [AuthController::class, 'sendEmailOtp']);
    Route::post('/email/otp/verify', [AuthController::class, 'verifyOtp']);
    Route::post('/social/login', [AuthController::class, 'socialLogin']);

//    Route::get('/auth/google', function () {
//        return Socialite::driver('google')->stateless()->redirect();
//    });
//    Route::get('/auth/google/callback', function (Request $request) {
//        try {
//            $googleUser = Socialite::driver('google')->stateless()->user();
//
//            $user = \App\Models\User::updateOrCreate([
//                'email' => $googleUser->getEmail(),
//            ], [
//                'name' => $googleUser->getName(),
//                'google_id' => $googleUser->getId(),
//                'avatar' => $googleUser->getAvatar(),
//            ]);
//
//            $token = $user->createToken('authToken')->plainTextToken;
//
//            return response()->json(['token' => $token, 'user' => $user]);
//
//        } catch (\Exception $e) {
//            return response()->json(['error' => 'Unauthorized'], 401);
//        }
//    });

    Route::middleware(['auth:sanctum'])->group(function () {

        Route::prefix('/user')->group(function () {
            Route::prefix('/profile')->group(function () {
                Route::get('/', [ProfileUpdateController::class, 'UserProfileShow']); // Get user profile
                Route::put('/update', [ProfileUpdateController::class, 'userProfileUpdate']); // Update user profile
            });
        });
        Route::prefix('/rider')->group(function () {
            Route::prefix('/profile')->group(function () {
                Route::get('/', [ProfileUpdateController::class, 'UserRiderProfileShow']); // Get user profile
                Route::put('/update', [ProfileUpdateController::class, 'riderProfileUpdate']); // Update user profile
            });
            Route::post('/location/update', [RiderLocationController::class, 'locationUpdate']);
            Route::get('/order/new/order/bid/list', [BidsController::class, 'newBidList']); // show
            Route::get('/order/new/bids/{order_id}', [BidsController::class, 'newBids']); // show
            Route::post('/order/apply/bids/{order_id}', [BidsController::class, 'applyBids']); // show


            Route::get('/my/new/order/request', [MyDeliveryController::class, 'myNewOrderRequest']); // show
            Route::get('/my/completed/order', [MyDeliveryController::class, 'myCompletedOrderList']); // show
            // ongoin order
            Route::get('/my/ongoing/order', [MyDeliveryController::class, 'myOngoingOrder']); // show
            Route::get('/my/ongoing/order/details/{order_id}', [MyDeliveryController::class, 'myOngoingOrderDetails']); // show

            // order otp verify
            Route::get('order/send/otp/{order_id}/{otp_type}', [OrderRequestController::class, 'riderOrderSendOtp']);
            Route::get('order/verify/{order_id}/{otp_type}/{otp_code}', [OrderRequestController::class, 'riderOrderOtpVerify']);

        });
        Route::prefix('/orders')->group(function () {
            Route::prefix('/new/order/request')->group(function () {
                Route::post('/', [OrderRequestController::class, 'orderRequest']); // Get user profile
                Route::get('/ongoing/list/{orderType}', [OrderRequestController::class, 'onGoingOrderList']); // Get user profile
                Route::get('/show/{order_id}', [OrderRequestController::class, 'showOrderRequest']);
            });
            Route::get('/my/completed/order/list', [OrderRequestController::class, 'myCompletedOrderList']);
            Route::get('my/order/details/{order_id}', [OrderRequestController::class, 'myOrderDetails']);
            Route::get('confirmed/order/{order_id}/{sub_order_id}/{rider_id}', [OrderRequestController::class, 'orderBidAccept']);
            Route::get('tracking/order/{order_id}', [OrderRequestController::class, 'orderTracking']);
            Route::get('packages', [OrderRequestController::class, 'packages']);

        });
        // payment
        Route::prefix('/orders')->group(function () {
            Route::get('stripe/payment/process', [StripePaymentController::class, 'stripePayment']);
        });
    });




});
