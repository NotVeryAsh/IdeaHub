<?php

namespace Teams;

use App\Models\Team;
use App\Models\User;
use Tests\TestCase;

class TeamShowViewTest extends TestCase
{
    public function test_user_can_access_show_team_page()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/teams')
            ->assertViewIs('teams.index')
            ->assertSeeInOrder([
                'Create Team',
                'Your Teams',
            ]);
    }

    public function test_correct_data_is_listed_on_page()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create([
            'creator_id' => $user->id,
            'name' => 'Acme',
        ]);

        $this->actingAs($user)->get("/teams/$team->id")
            ->assertSee('Acme');
    }

    public function test_only_one_team_is_displayed_on_page()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create([
            'creator_id' => $user->id,
            'name' => 'Acme',
        ]);

        Team::factory()->create([
            'creator_id' => $user->id,
            'name' => 'Acme2',
        ]);

        $this->actingAs($user)->get("/teams/$team->id")
            ->assertSee('Acme')
            ->assertDontSee('Acme2');
    }

    public function test_authentication_is_required_to_show_team_page()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create([
            'creator_id' => $user->id,
            'name' => 'Acme2',
        ]);

        $this->get("/teams/$team->id")
            ->assertRedirect('/login');
    }

    public function test_404_returned_when_team_is_not_found()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->get('/teams/1')
            ->assertStatus(404);
    }
}
