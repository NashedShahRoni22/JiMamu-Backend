<?php

use App\Http\Controllers\Admin\BannerController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\RiderAccountReviewController;
use App\Http\Controllers\Admin\UserDashboardController;

Route::get('/test', function () {

    return 'test';
});

Route::get('/admin', function () {

    return Inertia::render('welcome');
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



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
