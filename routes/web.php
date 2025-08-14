<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\OrderController;

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
    // order
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('orders.index');
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
