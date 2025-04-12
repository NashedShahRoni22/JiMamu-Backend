<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/admin', function () {

    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});
Route::get('/migrate', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate --force');  // Run migrations without removing existing data
        return 'Migrations run successfully!';
    } catch (\Exception $e) {
        return $e->getMessage();
    }
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
