<?php

namespace Teams;

use App\Models\Team;
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
    }

    public function test_members_are_listed_on_members_page()
    {

    }

    public function test_cannot_view_member_list_if_not_in_team()
    {

    }

    public function test_members_cannot_see_remove_button_for_members()
    {

    }

    public function test_members_cannot_see_remove_button_for_invitations()
    {

    }

    public function test_members_cannot_see_invite_button()
    {

    }

    public function test_owner_can_see_invite_button()
    {

    }

    public function test_owner_can_see_remove_button_for_members()
    {

    }

    public function test_owner_can_see_remove_button_for_invitations()
    {

    }
}
