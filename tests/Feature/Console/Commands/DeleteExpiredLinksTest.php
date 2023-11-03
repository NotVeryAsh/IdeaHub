<?php

namespace Tests\Feature\Console\Commands;

use App\Models\Team;
use App\Models\TeamLink;
use App\Models\User;
use Tests\TestCase;

class DeleteExpiredLinksTest extends TestCase
{

    public function test_expired_links_are_deleted()
    {
        User::factory()->create();

        Team::factory()->create();

        $link = TeamLink::factory()->create([
            'expires_at' => now()->subDay(),
        ]);

        $linkTwo = Teamlink::factory()->create([
            'expires_at' => now()->subDay(),
        ]);

        $this->artisan('links:delete-expired')
            ->expectsOutput('Deleting expired links...')
            ->expectsOutput('Deleted 2 expired links.')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('team_links', [
            'id' => $link->id,
        ]);

        $this->assertDatabaseMissing('team_links', [
            'id' => $linkTwo->id,
        ]);
    }

    public function test_valid_links_are_not_deleted()
    {
        User::factory()->create();

        Team::factory()->create();

        $link = Teamlink::factory()->create([
            'expires_at' => now()->addDay(),
        ]);

        $this->artisan('links:delete-expired')
            ->expectsOutput('Deleting expired links...')
            ->expectsOutput('Deleted 0 expired links.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('team_links', [
            'id' => $link->id,
        ]);
    }
}
