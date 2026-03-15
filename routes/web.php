<?php

use App\Http\Controllers\Admin\BannerController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\RiderAccountReviewController;
use App\Http\Controllers\Admin\UserDashboardController;
use App\Models\DeviceToken;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;


Route::get('/test', function () {

    return 'test';
});
Route::get('/admin', function () {

   // return Inertia::render('welcome');
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return Inertia::render('auth/login');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
    Route::prefix('banner')->group(function () {
        Route::get('/', [BannerController::class, 'index'])->name('banner.index');
        Route::get('/create', [BannerController::class, 'create'])->name('banner.create');
        Route::post('/store', [BannerController::class, 'store'])->name('banner.store');
        Route::get('/edit/{id}', [BannerController::class, 'edit'])->name('banner.edit');
        Route::post('/update/{id}', [BannerController::class, 'update'])->name('banner.update');
    });

    // order
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/show/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::get('/status/{order}', [OrderController::class, 'status'])->name('orders.status');
    });
    Route::prefix('riders')->name('riders.')->group(function () {
        Route::get('/rider/account/review', [RiderAccountReviewController::class, 'accountCreateRequest'])->name('account.review.request');
        Route::get('/rider/account/review/details/{order}', [RiderAccountReviewController::class, 'accountReviewDetails'])->name('rider.account.review.details');
        Route::get('rider/account/approve/{user_id}/{status_type}', [RiderAccountReviewController::class, 'accountApprove'])->name('rider.account.approve');
    });
    // user dashboard
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/dashboard/{user_id}', [UserDashboardController::class, 'userDashboard'])->name('dashboard');
        Route::get('/dashboard/orders/{user_id}', [UserDashboardController::class, 'userOrders'])->name('dashboard.user.orders');
        Route::get('/dashboard/rider/order/{user_id}', [UserDashboardController::class, 'riderOrders'])->name('dashboard.rider.orders');
    });
    Route::prefix('user/report')->name('users.report.')->group(function () {
        Route::get('/dashboard/{user_id}', [UserDashboardController::class, 'userDashboard'])->name('dashboard');
        Route::get('/requested/orders/{user_id}', [UserDashboardController::class, 'requestedOrder'])->name('requested.order');
        Route::get('/wallet/{user_id}', [UserDashboardController::class, 'wallet'])->name('wallet');
        Route::get('/dashboard/rider/order/{user_id}', [UserDashboardController::class, 'riderOrders'])->name('dashboard.rider.orders');
    });
});


Route::get('/migrate', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate --force');  // Run migrations without removing existing data
        return 'Migrations run successfully!';
    } catch (\Exception $e) {
        return $e->getMessage();
    }
});

Route::get('/migrate/refresh', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:refresh --seed --force');  // Run migrations without removing existing data
        return 'Migrations run successfully!';
    } catch (\Exception $e) {
        return $e->getMessage();
    }
});
Route::get('/storage-link', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        return 'Storage link created successfully!';
    } catch (\Exception $e) {
        return $e->getMessage();
    }
});
Route::get('/clear', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('optimize:clear'); // This clears config, route, view, event, compiled files
        return 'All caches cleared successfully!';
    } catch (\Exception $e) {
        return $e->getMessage();
    }
});

Route::get('/send-to-android', function (Messaging $messaging) {

   // $token = 'cy66sq9xSN-OeYyoXd2TZV:APA91bFq3FfAt2OICkYoI7dfoZQ11L8zUAjgaAHXQYWsAflOuUNc0n3ctAGGcgVTtTHCMItnt1I1_kwbDO_oGin48s0CKIELBUGFSbK-zu2XeQZ7vUiqpyA';
    $token = 'fryMYvnpTi2Tav9PAuQspt:APA91bFpfVUkLTFFlqWZP_GsJDwCRQyzTxoqPGyuG-cNERHbbuGHgsAL5N55l9HejuASUV2o227tjlTMlwIxrw9CDv51uVGoALEGt7i2Y92ZkTjkrmPLH4Y';

    $message = CloudMessage::withTarget('token', $token)
        ->withNotification(
            Notification::create('New Order 🛒', 'You received a new order')
        )
        ->withData([
            'route'    => '/orderDetails',
            'type'     => 'new_order_created',
            'order_id' => '123',
        ]);

    $messaging->send($message);

    return response()->json(['status' => '✅ Sent!']);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
