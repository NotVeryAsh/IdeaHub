<?php

namespace Tests\Feature\Console\Commands;

use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use Tests\TestCase;

class DeleteExpiredInvitationsTest extends TestCase
{
    public function test_recently_expired_invitations_are_not_deleted()
    {
        User::factory()->create();

        Team::factory()->create();

        // Create invitations that have been expired for just under a week
        $invitation = TeamInvitation::factory()->create([
            'expires_at' => now()->subDays(6),
        ]);

        $invitationTwo = TeamInvitation::factory()->create([
            'expires_at' => now()->subDays(6),
        ]);

        // Assert that invitations have not been deleted
        $this->artisan('invitations:delete-expired')
            ->expectsOutput('Deleting expired invitations...')
            ->expectsOutput('Deleted 0 expired invitations.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('team_invitations', [
            'id' => $invitation->id,
        ]);

        $this->assertDatabaseHas('team_invitations', [
            'id' => $invitationTwo->id,
        ]);
    }

    public function test_invitations_that_have_been_expired_for_a_week_are_deleted()
    {
        User::factory()->create();

        Team::factory()->create();

        // Create invitations that have been expired for over a week
        $invitation = TeamInvitation::factory()->create([
            'expires_at' => now()->subWeek(),
        ]);

        $invitationTwo = TeamInvitation::factory()->create([
            'expires_at' => now()->subWeek(),
        ]);

        // Assert that invitations have been deleted
        $this->artisan('invitations:delete-expired')
            ->expectsOutput('Deleting expired invitations...')
            ->expectsOutput('Deleted 2 expired invitations.')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('team_invitations', [
            'id' => $invitation->id,
        ]);

        $this->assertDatabaseMissing('team_invitations', [
            'id' => $invitationTwo->id,
        ]);
    }

    public function test_valid_invitations_are_not_deleted()
    {
        User::factory()->create();

        Team::factory()->create();

        $invitation = TeamInvitation::factory()->create([
            'expires_at' => now()->addDay(),
        ]);

        $this->artisan('invitations:delete-expired')
            ->expectsOutput('Deleting expired invitations...')
            ->expectsOutput('Deleted 0 expired invitations.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('team_invitations', [
            'id' => $invitation->id,
        ]);
    }
}
