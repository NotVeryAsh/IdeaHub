<?php

namespace Teams;

use App\Models\Team;
use App\Models\TeamUser;
use App\Models\User;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DeleteTeamTest extends TestCase
{
    public function test_team_can_be_deleted()
    {
        // Fake carbon time to now
        Carbon::setTestNow();

        $user = User::factory()->create();

        $team = Team::factory()->create();

        // Authenticate as team creator
        $this->actingAs($user);

        $response = $this->delete("/teams/{$team->id}");

        // Check that model is deleted now
        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'deleted_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirectToRoute('teams.index');

        $response->assertSessionHas(['status' => 'Team deleted successfully!']);
    }

    public function test_team_can_only_be_deleted_by_team_creator()
    {
        // Create creator of the team
        $user = User::factory()->create();

        // Create team
        $team = Team::factory()->create();

        // Create member for team
        $member = User::factory()->create();

        // Add member to team
        TeamUser::factory()->create([
            'team_id' => $team->id,
            'user_id' => $member->id,
        ]);

        // Authenticate as team member
        $this->actingAs($member);

        // Attempt to delete the invitation as a member
        $response = $this->delete("/teams/{$team->id}");

        $response->assertStatus(404);
    }

    public function test_authentication_is_required_to_delete_a_team()
    {
        User::factory()->create();
        $team = Team::factory()->create();

        $response = $this->delete("/teams/{$team->id}");

        $response->assertRedirect('/login');
    }

    public function test_404_returned_when_team_does_not_exist()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->delete('/teams/1');

        $response->assertStatus(404);
    }
}
