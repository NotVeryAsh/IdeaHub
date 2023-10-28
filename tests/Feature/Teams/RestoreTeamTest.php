<?php

namespace Teams;

use App\Models\Team;
use App\Models\TeamUser;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class RestoreTeamTest extends TestCase
{
    public function test_can_restore_team()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $team = Team::factory()->create([
            'deleted_at' => Carbon::now(),
        ]);

        $response = $this->patch("/teams/$team->id/restore");

        $response->assertRedirect('/teams');
        $response->assertSessionHas('status', 'Team restored successfully!');

        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'deleted_at' => null,
        ]);
    }

    public function test_only_team_creator_can_restore_team()
    {
        $user = User::factory()->create();

        $timeNow = Carbon::now()->format('Y-m-d H:i:s');

        $team = Team::factory()->create([
            'deleted_at' => $timeNow,
        ]);

        $member = User::factory()->create();
        $this->actingAs($member);

        TeamUser::factory()->create([
            'user_id' => $member->id,
            'team_id' => $team->id,
        ]);

        // Attempt to delete the team has a team member
        $response = $this->patch("/teams/$team->id/restore");

        $response->assertStatus(404);

        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'deleted_at' => $timeNow,
        ]);
    }

    public function test_authentication_is_required_to_restore_team()
    {
        User::factory()->create();

        $team = Team::factory()->create([
            'deleted_at' => Carbon::now(),
        ]);

        $response = $this->patch("/teams/$team->id/restore");

        $response->assertRedirect('/login');
    }

    public function test_404_returned_when_team_is_not_found()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->patch('/teams/1');

        $response->assertStatus(404);
    }
}
