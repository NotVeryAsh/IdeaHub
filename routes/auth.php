<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

// Logout and Email verification routes
Route::prefix('auth')->middleware('auth')->group(function () {

    // Logout route
    Route::post('logout', LogoutController::class)->name('logout');

    // Email verification routes
    Route::prefix('verify-email')->group(function () {

        Route::get('', [EmailVerificationController::class, 'showNotice'])->name('verification.notice');
        Route::post('resend', [EmailVerificationController::class, 'resend'])->middleware(['throttle:6,1'])->name('verification.send');
        Route::get('{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware(['signed'])->name('verification.verify');
    });
});

// Login register, and forgot password routes
Route::middleware('guest')->group(function () {

    // Authentication routes
    Route::prefix('auth')->group(function () {

        Route::post('login', [LoginController::class, 'authenticate']);
        Route::post('register', [RegisterController::class, 'authenticate']);
        Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkNotification'])->name('password.email');
    });

    // Index routes for views
    Route::get('login', [LoginController::class, 'index'])->name('login');
    Route::get('register', [RegisterController::class, 'index'])->name('register');
    Route::get('forgot-password', [ForgotPasswordController::class, 'index'])->name('forgot-password')->name('password.request');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
});
