<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Documentation\DocumentationController;
use App\Http\Controllers\Documentation\Laravel\RequestLifecycleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Profile\ProfilePictureController;
use App\Http\Controllers\Profile\SelectDefaultProfilePictureController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home route
Route::get('', [HomeController::class, 'index'])->name('home');

// Docs routes
Route::prefix('docs')->group(function () {
    Route::get('', [DocumentationController::class, 'index'])->name('docs.index');

    Route::prefix('architecture')->group(function () {
        Route::get('http-verbs', [RequestLifecycleController::class, 'index'])->name('docs.architecture.http-verbs');
    });

    Route::prefix('laravel')->group(function () {
        Route::get('request-lifecycle', [RequestLifecycleController::class, 'index'])->name('docs.architecture.request-lifecycle');
    });
});

// Auth routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard route
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::prefix('profile')->group(function () {

        Route::get('edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('{user:username?}', [ProfileController::class, 'index'])->name('profile');
    });

    // profile picture routes
    Route::prefix('profile-picture')->group(function () {
        Route::patch('', [ProfilePictureController::class, 'update'])->name('profile.profile-picture.update')->middleware('optimizeImages');
        Route::delete('', [ProfilePictureController::class, 'destroy'])->name('profile.profile-picture.delete');

        Route::prefix('default')->group(function () {
            Route::patch('{picture}', SelectDefaultProfilePictureController::class)->name('profile.default-profile-picture.select');
        });
    });
});
