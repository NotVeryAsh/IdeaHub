<?php

namespace Teams;

use App\Models\Team;
use App\Models\TeamUser;
use App\Models\User;
use Tests\TestCase;

class LeaveTeamTest extends TestCase
{
    public function test_can_leave_team()
    {
        // Create team owner
        $user = User::factory()->create();

        // Create team
        $team = Team::factory()->create();

        // Create team member
        $member = User::factory()->create();

        $teamUser = TeamUser::factory()->create([
            'user_id' => $member->id,
        ]);

        self::actingAs($member);

        // Remove member from team
        $response = $this->delete("teams/{$team->id}/leave");

        $response->assertSessionHas([
            'status' => 'You have left the team!',
        ]);

        $this->assertDatabaseMissing('team_user', [
            'id' => $teamUser->id,
        ]);
    }

    public function test_404_returned_when_member_is_not_in_team()
    {
        // Create user and team
        $user = User::factory()->create();
        $team = Team::factory()->create();

        // Create member but don't add them to the team
        $member = User::factory()->create();

        self::actingAs($member);

        // Remove member from team
        $response = $this->delete("teams/{$team->id}/leave");

        $response->assertStatus(404);
    }

    public function test_cannot_leave_team_if_unauthenticated()
    {
        // Create team owner
        User::factory()->create();

        // Create team
        $team = Team::factory()->create();

        // Create team member
        $member = User::factory()->create();

        TeamUser::factory()->create([
            'user_id' => $member->id,
        ]);

        // Remove member from team
        $response = $this->delete("teams/{$team->id}/leave");

        $response->assertRedirect('/login');
    }

    public function test_404_returned_when_team_not_found()
    {
        // Create user and team
        $user = User::factory()->create();

        self::actingAs($user);

        // Remove member from team
        $response = $this->delete('teams/test/leave');

        $response->assertStatus(404);
    }
}
