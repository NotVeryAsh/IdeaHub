<?php

namespace Teams;

use App\Models\Team;
use App\Models\TeamLink;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class GetTeamLinkTest extends TestCase
{
    public function test_can_get_team_link_test()
    {
        $user = User::factory()->create();

        self::actingAs($user);

        $team = Team::factory()->create();

        $teamLink = TeamLink::factory()->create();

        $response = $this->get("/teams/{$team->id}/link");

        $response->assertStatus(200);

        $response->assertJson([
            'url' => URL::to("teams/join/$teamLink->token"),
            'message' => 'Join link copied!',
        ]);
    }

    public function test_only_creator_can_get_team_link()
    {
        // Create the creator of the team
        $creator = User::factory()->create();

        // Create another user
        $user = User::factory()->create();

        // Act as the creator
        $this->actingAs($user);

        // Create a team for the creator
        $team = Team::factory()->create([
            'creator_id' => $creator->id,
        ]);

        // Create a link for the team
        TeamLink::factory()->create([
            'team_id' => $team->id,
        ]);

        // Act as the user
        $this->actingAs($user);

        // Attempt to get the link for the team which the user does not have permission for
        $response = $this->get("/teams/{$team->id}/link");

        $response->assertStatus(404);
    }

    public function test_authentication_is_required_to_get_a_team_link()
    {
        User::factory()->create();

        $team = Team::factory()->create();

        $response = $this->get("/teams/{$team->id}/link");

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_404_returned_when_team_does_not_exist()
    {
        $user = User::factory()->create();

        self::actingAs($user);

        $response = $this->get('/teams/1/link');

        $response->assertStatus(404);
    }
}
