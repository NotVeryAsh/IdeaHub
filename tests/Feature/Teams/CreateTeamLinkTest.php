<?php

namespace Teams;

use App\Models\Team;
use App\Models\TeamLink;
use App\Models\User;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CreateTeamLinkTest extends TestCase
{
    public function test_creator_can_create_a_team_link()
    {
        // Fake carbon time so we can compare the expiration date
        Carbon::setTestNow();

        // Create the creator of the team
        $user = User::factory()->create();

        $this->actingAs($user);

        // Create team for the link
        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Create a join link for the team
        $response = $this->post("/teams/{$team->id}/links");

        // Check that user is redirected to team show page with a success message
        $response->assertRedirectToRoute('teams.members', $team);
        $response->assertSessionHas(['status' => 'Join link created!']);

        $this->assertDatabaseHas('team_links', [
            'team_id' => $team->id,
            'expires_at' => now()->addMonth(),
        ]);
    }

    public function test_old_link_is_deleted_when_a_new_one_is_created()
    {
        // Fake carbon time so we can compare the expiration date
        Carbon::setTestNow();

        // Create the creator of the team
        $user = User::factory()->create();

        $this->actingAs($user);

        // Create team for the link
        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        $link = TeamLink::factory()->create();

        // Create a join link for the team
        $this->post("/teams/{$team->id}/links");

        $this->assertDatabaseMissing('team_links', [
            'id' => $link->id,
        ]);

        $this->assertDatabaseHas('team_links', [
            'team_id' => $team->id,
            'expires_at' => now()->addMonth(),
        ]);
    }

    public function test_authentication_is_required_to_create_a_team_link()
    {
        User::factory()->create();

        $team = Team::factory()->create();

        $response = $this->post("/teams/{$team->id}/links");

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_creator_authorization_is_required_to_create_a_team_link()
    {
        // Create the creator of the team
        $creator = User::factory()->create();

        // Create another user
        $user = User::factory()->create();

        // Act as the user
        $this->actingAs($user);

        // Create a team for the creator
        $team = Team::factory()->create();

        // Attempt to create a link for the team which the user does not have permission for
        $response = $this->post("/teams/{$team->id}/links");

        $response->assertStatus(404);
    }
}
