<?php

namespace Tests\Feature\Console\Scheduler;

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

        $this->artisan('schedule:run')
            ->assertExitCode(0);
// TODO Fix this test
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

        $this->artisan('schedule:run')
            ->assertExitCode(0);

        $this->assertDatabaseHas('team_links', [
            'id' => $link->id,
        ]);
    }
}
