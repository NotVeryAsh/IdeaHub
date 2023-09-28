<?php

namespace Teams;

use App\Models\Team;
use App\Models\User;
use Tests\TestCase;

class ManageTeamsViewTest extends TestCase
{
    public function test_user_can_access_make_a_team_page()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/teams')
            ->assertViewIs('teams.index')
            ->assertSeeInOrder([
                'Create new teams',
                'Your teams',
                "Teams you're in",
            ]);
    }

    public function test_users_teams_are_listed_on_teams_page()
    {
        $user = User::factory()->create();

        Team::factory()->create([
            'creator_id' => $user->id,
            'name' => 'Acme',
        ]);

        Team::factory()->create([
            'creator_id' => $user->id,
            'name' => 'Acme 2',
        ]);

        $this->actingAs($user)->get('/teams')
            ->assertSee('Acme')
            ->assertSee('Acme 2');
    }

    public function test_only_users_teams_are_listed_on_teams_page()
    {
        // Create a user and a team
        $user = User::factory()->create();

        Team::factory()->create([
            'creator_id' => $user->id,
            'name' => 'Acme',
        ]);

        // Create another user and team
        $userTwo = User::factory()->create();

        Team::factory()->create([
            'name' => 'Acme 2',
            'creator_id' => $userTwo->id,
        ]);

        // list first user's teams
        $this->actingAs($user);
        $response = $this->get('/teams');

        // Assert see correct team
        $response->assertSee('Acme');

        // Assert don't see other user's team
        $response->assertDontSee('Acme 2');

        // TODO Check that teams that the user is part of show up
    }

    public function test_authentication_is_required_to_access_make_a_team_page()
    {
        $this->get('/teams/create')
            ->assertRedirect('/login');
    }
}
