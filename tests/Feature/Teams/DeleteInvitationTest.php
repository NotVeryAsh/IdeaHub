<?php

namespace Tests\Feature\Teams;

use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\TeamUser;
use App\Models\User;
use Tests\TestCase;

class DeleteInvitationTest extends TestCase
{
    public function test_teams_invitation_can_be_deleted()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        $teamInvitation = TeamInvitation::factory()->create([
            'team_id' => $team->id,
            'email' => 'test@test.com'
        ]);

        // Authenticate as team creator
        $this->actingAs($user);

        $response = $this->delete("/invitations/{$teamInvitation->id}");

        $response->assertRedirectToRoute('teams.members', $team);

        $response->assertSessionHas(['status' => 'Invitation deleted!']);
    }

    public function test_invitation_can_only_be_deleted_by_team_creator()
    {
        // Create creator of the team
        $user = User::factory()->create();

        // Create team
        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Create member for team
        $member = User::factory()->create();

        // Add member to team
        TeamUser::factory()->create([
            'team_id' => $team->id,
            'user_id' => $member->id,
        ]);

        // Create invitation for team
        $teamInvitation = TeamInvitation::factory()->create([
            'team_id' => $team->id,
            'email' => 'test@test.com'
        ]);

        // Authenticate as team member
        $this->actingAs($member);

        // Attempt to delete the invitation as a member
        $response = $this->delete("/invitations/{$teamInvitation->id}");

        $response->assertStatus(404);
    }

    public function test_authentication_is_required_to_delete_a_team_invitation()
    {
        User::factory()->create();
        $team = Team::factory()->create();

        $teamInvitation = TeamInvitation::factory()->create([
            'team_id' => $team->id,
            'email' => 'test@test.com'
        ]);

        $response = $this->delete("/invitations/{$teamInvitation->id}");

        $response->assertRedirect('/login');
    }

    public function test_404_returned_when_invitation_does_not_exist()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->delete('/invitations/1');

        $response->assertStatus(404);
    }
}
