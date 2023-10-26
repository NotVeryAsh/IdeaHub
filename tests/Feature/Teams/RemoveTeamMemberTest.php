<?php

namespace Tests\Feature\Teams;

use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\TeamUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tests\TestCase;

class RemoveTeamMemberTest extends TestCase
{
    public function test_can_remove_member_from_team()
    {
        // Create team owner
        $user = User::factory()->create();

        // Create team
        $team = Team::factory()->create();

        // Create team member
        $member = User::factory()->create();

        TeamUser::factory()->create([
            'user_id' => $member->id,
        ]);

        self::actingAs($user);

        // Remove member from team
        $response = $this->delete("teams/{$team->id}/members/{$member->id}");

        $response->assertSessionHas([
            'status' => 'Team member removed!'
        ]);
    }

    public function test_cannot_remove_member_from_team_if_not_team_owner()
    {
        // Create user and team
        $user = User::factory()->create();
        $team = Team::factory()->create();

        // Create member and add them to the team
        $member = User::factory()->create();
        TeamUser::factory()->create([
            'user_id' => $member->id,
        ]);

        // Authenticate as team member
        self::actingAs($member);

        // Remove member from team
        $response = $this->delete("teams/{$team->id}/members/{$member->id}");

        $response->assertStatus(404);
    }

    public function test_404_returned_when_member_is_not_in_team()
    {
        // Create user and team
        $user = User::factory()->create();
        $team = Team::factory()->create();

        // Create member but don't add them to the team
        $member = User::factory()->create();

        self::actingAs($user);

        // Remove member from team
        $response = $this->delete("teams/{$team->id}/members/{$member->id}");

        $response->assertStatus(404);
    }

    public function test_cannot_remove_member_if_unauthenticated()
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
        $response = $this->delete("teams/{$team->id}/members/{$member->id}");

        $response->assertRedirect('/login');
    }

    public function test_404_returned_when_team_not_found()
    {
        // Create user and team
        $user = User::factory()->create();

        self::actingAs($user);

        // Remove member from team
        $response = $this->delete("teams/test/members/$user->id");

        $response->assertStatus(404);
    }

    public function test_404_returned_when_member_not_found()
    {
        // Create user and team
        $user = User::factory()->create();
        $team = Team::factory()->create();

        self::actingAs($user);

        // Remove member from team
        $response = $this->delete("teams/{$team->id}/members/test");

        $response->assertStatus(404);
    }
}
