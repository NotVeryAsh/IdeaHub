<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth/verify-email')->group(function () {
    Route::get('', [EmailVerificationController::class, 'showNotice'])
        ->middleware('auth')->name('verification.notice');

    Route::post('resend', [EmailVerificationController::class, 'resend'])
        ->middleware(['auth', 'throttle:6,1'])->name('verification.send');

    Route::get('{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['auth', 'signed'])->name('verification.verify');
});
