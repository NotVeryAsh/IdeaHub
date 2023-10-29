<?php

namespace Teams;

use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\TeamUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
            ->assertSee('Members')
            ->assertSee('Invitations');
    }

    public function test_can_list_members_on_page_two()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Create first member and add them to the team
        $member = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'Name',
        ]);

        TeamUser::factory()->create([
            'team_id' => $team->id,
            'user_id' => $member->id,
        ]);

        // Create second member and add them to the team
        $memberTwo = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'Name',
        ]);

        TeamUser::factory()->create([
            'team_id' => $team->id,
            'user_id' => $memberTwo->id,
        ]);

        // Get page two results of users
        $response = $this->actingAs($user)
            ->get(Request::create("/teams/{$team->id}/members")
                ->fullUrlWithQuery([
                    'page' => 2,
                    'per_page' => 1,
                ])
            );

        // Assert can only
        $response->assertSee($memberTwo->email);
        $response->assertDontSee($member->email);
    }

    public function test_can_search_members_by_names()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        $member = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'Name',
        ]);

        TeamUser::factory()->create([
            'team_id' => $team->id,
            'user_id' => $member->id,
        ]);

        $response = $this->actingAs($user)
            ->get(Request::create("/teams/{$team->id}/members")
                ->fullUrlWithQuery([
                    'search_term' => 'Test Name',
                ])
            );

        $response->assertSee($member->email);
    }

    public function test_can_search_members_by_email()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        $member = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        TeamUser::factory()->create([
            'team_id' => $team->id,
            'user_id' => $member->id,
        ]);

        $response = $this->actingAs($user)
            ->get(Request::create("/teams/{$team->id}/members")
                ->fullUrlWithQuery([
                    'search_term' => 'test@test.com',
                ])
            );

        $response->assertSee($member->email);
    }

    public function test_can_search_members_by_username()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        $member = User::factory()->create([
            'username' => 'test_username',
        ]);

        TeamUser::factory()->create([
            'team_id' => $team->id,
            'user_id' => $member->id,
        ]);

        $response = $this->actingAs($user)
            ->get(Request::create("/teams/{$team->id}/members")
                ->fullUrlWithQuery([
                    'search_term' => 'test_username',
                ])
            );

        $response->assertSee($member->email);
    }

    public function test_can_order_members_by_date_joined()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Create members for the team
        $member = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'Name',
        ]);

        $memberTwo = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'Name',
        ]);

        // Add members to the team
        TeamUser::factory()->create([
            'team_id' => $team->id,
            'user_id' => $member->id,
            'created_at' => now()->subDays(1),
        ]);

        TeamUser::factory()->create([
            'team_id' => $team->id,
            'user_id' => $memberTwo->id,
            'created_at' => now()->subDays(2),
        ]);

        $response = $this->actingAs($user)
            ->get(Request::create("/teams/{$team->id}/members")
                ->fullUrlWithQuery([
                    'order_by' => 'date_joined',
                ])
            );

        $response->assertSeeTextInOrder([$memberTwo->email, $member->email]);
    }

    public function test_can_order_members_by_username()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Create members for the team
        $member = User::factory()->create([
            'username' => 'testuser2',
        ]);

        $memberTwo = User::factory()->create([
            'username' => 'testuser',
        ]);

        // Add members to the team
        TeamUser::factory()->create([
            'team_id' => $team->id,
            'user_id' => $member->id,
            'created_at' => now()->subDays(1),
        ]);

        TeamUser::factory()->create([
            'team_id' => $team->id,
            'user_id' => $memberTwo->id,
            'created_at' => now()->subDays(2),
        ]);

        $response = $this->actingAs($user)
            ->get(Request::create("/teams/{$team->id}/members")
                ->fullUrlWithQuery([
                    'order_by' => 'username',
                ])
            );

        $response->assertSeeTextInOrder([$memberTwo->email, $member->email]);
    }

    public function test_can_order_members_by_name()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Create members for the team
        $member = User::factory()->create([
            'first_name' => 'test',
            'last_name' => 'b',
        ]);

        $memberTwo = User::factory()->create([
            'first_name' => 'test',
            'last_name' => 'a',
        ]);

        // Add members to the team
        TeamUser::factory()->create([
            'team_id' => $team->id,
            'user_id' => $member->id,
            'created_at' => now()->subDays(1),
        ]);

        TeamUser::factory()->create([
            'team_id' => $team->id,
            'user_id' => $memberTwo->id,
            'created_at' => now()->subDays(2),
        ]);

        $response = $this->actingAs($user)
            ->get(Request::create("/teams/{$team->id}/members")
                ->fullUrlWithQuery([
                    'order_by' => 'name',
                ])
            );

        $response->assertSeeTextInOrder([$memberTwo->email, $member->email]);
    }

    public function test_invitations_are_listed_on_team_members_page()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        TeamInvitation::factory()->create([
            'team_id' => $team->id,
            'email' => 'test@test.com',
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
            'email' => 'test@test.com',
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
            ->assertDontSee('Send Invitation');
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

    public function test_page_must_be_an_integer()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->get(Request::create("/teams/{$team->id}/members")
                ->fullUrlWithQuery([
                    'page' => 'string',
                ])
            );

        $response->assertStatus(302);

        $response->assertSessionHasErrors([
            'page' => 'Page must be an integer.',
        ]);
    }

    public function test_per_page_must_be_an_integer()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->get(Request::create("/teams/{$team->id}/members")
                ->fullUrlWithQuery([
                    'per_page' => 'string',
                ])
            );

        $response->assertStatus(302);

        $response->assertSessionHasErrors([
            'per_page' => 'Per page must be an integer.',
        ]);
    }

    public function test_search_term_must_not_be_greater_than_255_characters()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->get(Request::create("/teams/{$team->id}/members")
                ->fullUrlWithQuery([
                    'search_term' => Str::random(256),
                ])
            );

        $response->assertStatus(302);

        $response->assertSessionHasErrors([
            'search_term' => 'Search term must not be greater than 255 characters.',
        ]);
    }

    public function test_order_by_must_be_valid()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->get(Request::create("/teams/{$team->id}/members")
                ->fullUrlWithQuery([
                    'order_by' => 'test',
                ])
            );

        $response->assertStatus(302);

        $response->assertSessionHasErrors([
            'order_by' => 'Order by is invalid.',
        ]);
    }

    public function test_order_by_direction_must_be_valid()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->get(Request::create("/teams/{$team->id}/members")
                ->fullUrlWithQuery([
                    'order_by_direction' => 'test',
                ])
            );

        $response->assertStatus(302);

        $response->assertSessionHasErrors([
            'order_by_direction' => 'Order by direction is invalid.',
        ]);
    }
}
