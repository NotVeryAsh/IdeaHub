<?php

namespace Teams;

use App\Models\Team;
use App\Models\TeamUser;
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
                'Create Team',
                'Your Teams',
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
        // Create a user
        $user = User::factory()->create();

        // Create a team for the user
        Team::factory()->create([
            'creator_id' => $user->id,
            'name' => 'Acme',
        ]);

        // Create a second user
        $userTwo = User::factory()->create();

        // Create a team for the other user
        Team::factory()->create([
            'name' => 'Acme 2',
            'creator_id' => $userTwo->id,
        ]);

        // Create a third user
        $userThree = User::factory()->create();

        // Create a team for the third user
        $teamThree = Team::factory()->create([
            'name' => 'Acme 3',
            'creator_id' => $userThree->id,
        ]);

        // Make the first user join the third team
        TeamUser::factory()->create([
            'team_id' => $teamThree->id,
            'user_id' => $user->id,
        ]);

        // Get the teams for the first user
        $this->actingAs($user);
        $response = $this->get('/teams');

        // Assert that the user's team shows up
        $response->assertSee('Acme');

        // Assert that the other team's isn't showing up
        $response->assertDontSee('Acme 2');

        // Assert that the third team is showing up since the user is a member in it
        $response->assertSee('Acme 3');
    }

    public function test_authentication_is_required_to_access_make_a_team_page()
    {
        $this->get('/teams')
            ->assertRedirect('/login');
    }
}
