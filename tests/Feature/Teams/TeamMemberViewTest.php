<?php

namespace Teams;

use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\TeamUser;
use App\Models\User;
use Tests\TestCase;

class TeamMemberViewTest extends TestCase
{
    public function test_can_access_team_members_page()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        TeamUser::factory()->create([
            'team_id' => $team->id,
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->get("/teams/{$team->id}/members")
            ->assertSee($team->name)
            ->assertSee('Members')
            ->assertSee('Invitations');
    }

    public function test_invitations_are_listed_on_team_members_page()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        TeamInvitation::factory()->create([
            'team_id' => $team->id,
            'email' => 'test@test.com'
        ]);

        $this->actingAs($user)
            ->get("/teams/{$team->id}/members")
            ->assertSee('test@test.com');
    }

    public function test_members_are_listed_on_members_page()
    {
        $user = User::factory()->create();
        $member = User::factory()->create([
            'first_name' => 'Ash',
            'email' => 'test@test.com'
        ]);

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        TeamUser::factory()->create([
            'team_id' => $team->id,
            'user_id' => $member->id,
        ]);

        $this->actingAs($user)
            ->get("/teams/{$team->id}/members")
            ->assertSee('Ash')
            ->assertSee('test@test.com');
    }

    public function test_cannot_view_member_list_if_not_in_team()
    {
        $creator = User::factory()->create();

        $team = Team::factory()->create([
            'creator_id' => $creator->id,
        ]);

        // Create a user that is not in the team
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get("/teams/{$team->id}/members")
            ->assertStatus(404)
            ->assertDontSee($team->name);
    }

    public function test_members_cannot_see_remove_button_for_members()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Create team member
        $member = User::factory()->create();

        TeamUser::factory()->create([
            'team_id' => $team->id,
            'user_id' => $member->id,
        ]);

        $this->actingAs($member)
            ->get("/teams/{$team->id}/members")
            ->assertDontSee('Remove');
    }

    public function test_members_cannot_see_remove_button_for_invitations()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Create team member
        $member = User::factory()->create();

        TeamUser::factory()->create([
            'team_id' => $team->id,
            'user_id' => $member->id,
        ]);

        // Create invitation
        TeamInvitation::factory()->create();

        $this->actingAs($member)
            ->get("/teams/{$team->id}/members")
            ->assertDontSee('Delete');
    }

    public function test_members_cannot_see_invite_button()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Create team member
        $member = User::factory()->create();

        TeamUser::factory()->create([
            'team_id' => $team->id,
            'user_id' => $member->id,
        ]);

        // Create invitation
        TeamInvitation::factory()->create();

        $this->actingAs($member)
            ->get("/teams/{$team->id}/members")
            ->assertDontSee('Invite');
    }

    public function test_owner_can_see_invite_button()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->get("/teams/{$team->id}/members")
            ->assertSee('Invite');
    }

    public function test_owner_can_see_remove_button_for_members()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Create team member
        $member = User::factory()->create();

        TeamUser::factory()->create([
            'team_id' => $team->id,
            'user_id' => $member->id,
        ]);

        $this->actingAs($user)
            ->get("/teams/{$team->id}/members")
            ->assertSee('Remove');
    }

    public function test_owner_can_see_remove_button_for_invitations()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Create invitation
        TeamInvitation::factory()->create();

        $this->actingAs($user)
            ->get("/teams/{$team->id}/members")
            ->assertSee('Delete');
    }
}
