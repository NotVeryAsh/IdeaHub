<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Docs\Architecture\HttpVerbsController;
use App\Http\Controllers\HomeController;
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

Route::prefix('docs')->group(function () {
    Route::prefix('architecture')->group(function () {
        Route::get('http-verbs', [HttpVerbsController::class, 'index'])->name('docs.architecture.http-verbs');
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
