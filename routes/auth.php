<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {

    Route::prefix('verify-email')->middleware('auth')->group(function () {
        Route::get('', [EmailVerificationController::class, 'showNotice'])
            ->name('verification.notice');

        Route::post('resend', [EmailVerificationController::class, 'resend'])
            ->middleware(['throttle:6,1'])
            ->name('verification.send');

        Route::get('{id}/{hash}', [EmailVerificationController::class, 'verify'])
            ->middleware(['signed'])
            ->name('verification.verify');
    });

    Route::post('login', [LoginController::class, 'authenticate'])
        ->middleware('guest')
        ->name('login');
});
