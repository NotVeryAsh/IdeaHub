<?php

use App\Http\Controllers\Teams\TeamInvitationsController;
use App\Http\Controllers\Teams\TeamLinksController;
use App\Http\Controllers\Teams\TeamMembersController;
use App\Http\Controllers\Teams\TeamsController;
use App\Models\TeamInvitation;
use App\Models\TeamLink;
use Illuminate\Support\Facades\Route;

// Team Routes

// Route to accept invitation
Route::prefix('invitations')->group(function () {
    Route::prefix('{token}')->group(function () {
        Route::get('', [TeamInvitationsController::class, 'accept'])->name('invitations.accept');
    });
});

Route::prefix('teams')->group(function () {
    Route::prefix('join')->group(function () {
        Route::get('{token}', [TeamLinksController::class, 'join'])->name('links.join');
    });
});

// Team routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('teams')->group(function () {
        Route::get('', [TeamsController::class, 'index'])->name('teams.index');
        Route::post('', [TeamsController::class, 'store'])->name('teams.store');

        Route::prefix('{team}')->group(function () {
            Route::get('', [TeamsController::class, 'show'])->name('teams.show')->can('view', 'team');
            Route::patch('', [TeamsController::class, 'update'])->name('teams.update')->can('update', 'team');
            Route::delete('', [TeamsController::class, 'delete'])->name('teams.delete')->can('delete', 'team')->withTrashed();
            Route::patch('restore', [TeamsController::class, 'restore'])->name('teams.restore')->can(
                'restore',
                'team'
            )->withTrashed();

            Route::delete('leave', [TeamMembersController::class, 'leave'])->name('teams.leave')->can(
                'TeamUserGate.leave',
                ['team', 'member']
            );

            Route::prefix('members')->group(function () {
                Route::get('', [TeamMembersController::class, 'index'])->name('teams.members')->can(
                    'TeamUserGate.viewAny',
                    ['team']
                );

                Route::prefix('{member}')->group(function () {
                    Route::delete('', [TeamMembersController::class, 'remove'])->name('teams.members.remove')->can(
                        'TeamUserGate.delete',
                        ['team', 'member']
                    );
                });
            });

            // Team invitations routes
            Route::prefix('invitations')->group(function () {
                Route::get('', [TeamInvitationsController::class, 'index'])->name('invitations.index')->can(
                    'viewAny',
                    [TeamInvitation::class, 'team']);
                Route::post('', [TeamInvitationsController::class, 'store'])->name('invitations.store')->can(
                    'create',
                    [TeamInvitation::class, 'team']
                );
            });

            // Team link routes
            Route::prefix('link')->group(function () {
                Route::post('', [TeamLinksController::class, 'store'])->name('links.store')->can(
                    'create',
                    [TeamLink::class, 'team']
                );
                Route::get('', [TeamLinksController::class, 'show'])->name('links.show')->can(
                    'view',
                    [TeamLink::class, 'team']
                );
            });
        });
    });

    // Route to delete invitation
    Route::prefix('invitations')->group(function () {
        Route::prefix('{team_invitation}')->group(function () {
            Route::delete('', [TeamInvitationsController::class, 'delete'])->name('invitations.delete')->can(
                'delete',
                'team_invitation'
            );
        });
    });
});
