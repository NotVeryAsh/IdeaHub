<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

// Email verification and logout routes
Route::prefix('auth')->group(function () {
    Route::prefix('verify-email')->middleware('auth')->group(function () {

        Route::get('', [EmailVerificationController::class, 'showNotice'])->name('verification.notice');
        Route::post('resend', [EmailVerificationController::class, 'resend'])->middleware(['throttle:6,1'])->name('verification.send');
        Route::get('{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware(['signed'])->name('verification.verify');
    });

    Route::post('logout', LogoutController::class)->name('logout');
});

// Login and dashboard routes
Route::middleware('guest')->group(function () {

    // Authentication routes
    Route::prefix('auth')->group(function () {

        Route::post('login', [LoginController::class, 'authenticate']);
        Route::post('register', [RegisterController::class, 'authenticate']);
    });

    // Index routes for views
    Route::get('login', [LoginController::class, 'index'])->name('login');
    Route::get('register', [RegisterController::class, 'index'])->name('register');
});
