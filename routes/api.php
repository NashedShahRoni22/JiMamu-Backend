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
use App\Http\Controllers\Api\Payment\StripePaymentController;
use App\Http\Controllers\Api\Payment\StripeWebhookController;
use App\Http\Controllers\Api\Wallet\WalletController;
use App\Http\Controllers\Api\Order\OrderCancelController;
use App\Http\Controllers\Api\Order\InternationalOrderController;
use App\Http\Controllers\Api\Order\OrderManageController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Api\Rider\BankInformationController;

Route::middleware(['json.response'])->prefix('/v1')->group(function() {
    Route::post('/send/email/otp', [AuthController::class, 'sendEmailOtp']);
    Route::post('/email/otp/verify', [AuthController::class, 'verifyOtp']);
    Route::post('/social/login', [AuthController::class, 'socialLogin']);

    Route::post('/webhook/stripe', [StripeWebhookController::class, 'handleWebhook']);


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
            Route::get('/my/order/applied/bids/{order_type}', [BidsController::class, 'myOrderAppliedBids']);

            // bank information
            Route::prefix('/bank')->group(function () {
                Route::get('index', [BankInformationController::class, 'index']);
                Route::post('store', [BankInformationController::class, 'store']);
                Route::put('update/{id}', [BankInformationController::class, 'update']);
            });


            Route::get('/my/new/order/request', [MyDeliveryController::class, 'myNewOrderRequest']); // show
            Route::get('/my/completed/order', [MyDeliveryController::class, 'myCompletedOrderList']); // show
            // ongoing order
            Route::get('/my/ongoing/order', [MyDeliveryController::class, 'myOngoingOrder']); // show
            Route::get('/my/ongoing/order/details/{order_id}', [MyDeliveryController::class, 'myOngoingOrderDetails']); // show

            // order otp verify
            Route::get('order/send/otp/{order_id}/{otp_type}', [OrderRequestController::class, 'riderOrderSendOtp']);
            Route::get('order/verify/{order_id}/{otp_type}/{otp_code}', [OrderRequestController::class, 'riderOrderOtpVerify']);
            // cancel order
            Route::post('order/cancel/{order_id}', [OrderCancelController::class, 'riderOrderCancel']);

        });
        Route::prefix('/orders')->group(function () {
            Route::get('/zone/list', [OrderRequestController::class, 'zoneList']);
            Route::get('/parcel/weight/list', [OrderRequestController::class, 'weightList']);;
            Route::prefix('/new/order/request')->group(function () {
                Route::post('/', [OrderRequestController::class, 'orderRequest']); // Get user profile
                Route::get('/ongoing/list/', [OrderRequestController::class, 'onGoingOrderList']); // Get user profile
                Route::get('/show/{order_id}', [OrderRequestController::class, 'showOrderRequest']);
            });
            Route::get('/my/completed/order/list', [OrderRequestController::class, 'myCompletedOrderList']);
            Route::get('my/order/details/{order_id}', [OrderRequestController::class, 'myOrderDetails']);
            Route::get('confirmed/order/{order_id}/{sub_order_id}/{rider_id}', [OrderRequestController::class, 'orderBidAccept']);
            Route::get('tracking/order/{order_id}', [OrderRequestController::class, 'orderTracking']);
            Route::get('packages', [OrderRequestController::class, 'packages']);

            Route::post('order/cancel/{order_id}', [OrderCancelController::class, 'cancelOrder']);
            Route::get('order/cancel/reason/list', [OrderCancelController::class, 'orderCancelReason']);


            // international order request
            Route::post('international/order/request', [InternationalOrderController::class, 'internationalOrderRequest']);
            Route::get('international/ongoing/order/list', [InternationalOrderController::class, 'internationalOngoingOrderList']);

            // ***** national and internation both order *****
            // order bid accepted cancel


        });

        // customer and rider

        Route::get('/cancel/order/accepted/bid/{order_id}/{rider_id}', [OrderManageController::class, 'acceptedBidCancel']); // only order bid accepted cancel

        Route::get('/order/pricing/rate', [OrderManageController::class, 'orderPricingRate']);
        Route::get('/order/overview', [OrderManageController::class, 'orderOverview']);

        // payments
        Route::prefix('/payments')->group(function () {
            Route::post('/create/payment-intent', [StripePaymentController::class, 'createPaymentIntent']);
        });
        // wallets
        Route::prefix('wallets')->group(function () {
            Route::get('/', [WalletController::class, 'wallet']);
            Route::get('history', [WalletController::class, 'walletHistory']);
            Route::post('withdrawal', [WalletController::class, 'walletWithdrawal']);
            Route::get('/processing', [WalletController::class, 'walletProcessing']);
        });

        // get all banner
        Route::get('/banners', [BannerController::class, 'getAllBanner']);
    });




});
