<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Docs\Architecture\HttpVerbsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Profile\ProfilePictureController;
use App\Http\Controllers\Profile\SelectDefaultProfilePictureController;
use App\Http\Controllers\Teams\TeamInvitationsController;
use App\Http\Controllers\Teams\TeamMembersController;
use App\Http\Controllers\Teams\TeamsController;
use App\Models\TeamInvitation;
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
    Route::prefix('architecture')->group(function () {
        Route::get('http-verbs', [HttpVerbsController::class, 'index'])->name('docs.architecture.http-verbs');
    });
});

// Route to accept invitation
Route::prefix('invitations')->group(function () {
    Route::prefix('{token}')->group(function () {
        Route::get('', [TeamInvitationsController::class, 'accept'])->name('invitations.accept');
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

    // TODO - Add can() method to check if user is in team

    // Team routes
    Route::prefix('teams')->group(function () {
        Route::get('', [TeamsController::class, 'index'])->name('teams.index');
        Route::post('', [TeamsController::class, 'store'])->name('teams.store');

        Route::prefix('{team}')->group(function () {
            Route::get('', [TeamsController::class, 'show'])->name('teams.show')->can('view', 'team');

            Route::prefix('members')->group(function () {
                Route::get('', [TeamMembersController::class, 'index'])->name('teams.members')->can('TeamUserGate.viewAny', ['team']);

                Route::prefix('{member}')->group(function () {
                    Route::delete('', [TeamMembersController::class, 'remove'])->name('teams.members.remove')->can('TeamUserGate.delete', ['team', 'member']);
                });
            });

            // Team invitations routes
            Route::prefix('invitations')->group(function () {
                Route::get('', [TeamInvitationsController::class, 'index'])->name('invitations.index');
                Route::post('', [TeamInvitationsController::class, 'store'])->name('invitations.store')->can('create', [TeamInvitation::class, 'team']);
            });
        });
    });

    // Route to delete invitation
    Route::prefix('invitations')->group(function () {
        Route::prefix('{team_invitation}')->group(function () {
            Route::delete('', [TeamInvitationsController::class, 'delete'])->name('invitations.delete')->can('delete', 'team_invitation');
        });
    });
});
