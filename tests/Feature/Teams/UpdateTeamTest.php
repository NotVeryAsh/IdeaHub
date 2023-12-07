<?php

namespace Teams;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class UpdateTeamTest extends TestCase
{
    public function test_user_can_update_a_team()
    {
        Mail::fake();

        $user = User::factory()->create();

        $team = Team::factory()->create([
            'name' => 'Acme',
        ]);

        $this->actingAs($user);

        $response = $this->patch("/teams/$team->id", [
            'name' => 'Other Name',
        ]);

        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'name' => 'Other Name',
        ]);

        $response->assertRedirect("/teams/{$team->id}");
        $response->assertSessionHas([
            'status' => 'Team updated successfully!',
        ]);
    }

    public function test_name_must_be_a_string_when_updating_a_team()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create();

        $response = $this->actingAs($user)->patch("/teams/$team->id", [
            'name' => 1234,
        ]);

        $response->assertSessionHasErrors(['name' => 'Team Name is invalid.']);
    }

    public function test_name_is_required_when_updating_a_team()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create();

        $response = $this->actingAs($user)->patch("/teams/$team->id", [
            'name' => '',
        ]);

        $response->assertSessionHasErrors(['name' => 'Team Name is required.']);
    }

    public function test_team_name_must_not_be_greater_than_255_characters()
    {
        $user = User::factory()->create();

        $team = Team::factory()->create();

        $response = $this->actingAs($user)->patch("/teams/$team->id", [
            'name' => Str::random(51),
        ]);

        $response->assertSessionHasErrors(['name' => 'Team Name must not be greater than 50 characters.']);
    }

    public function test_authentication_is_required_to_update_a_team()
    {
        User::factory()->create();

        $team = Team::factory()->create();

        $this->patch("/teams/$team->id", [
            'name' => 'Acme',
        ])->assertRedirect('/login');
    }
}
