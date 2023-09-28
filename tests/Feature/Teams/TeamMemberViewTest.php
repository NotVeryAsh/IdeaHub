<?php

namespace Teams;

use App\Mail\Teams\TeamCreated;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class TeamMemberViewTest extends TestCase
{
    public function test_user_can_create_a_team()
    {
        Mail::fake();

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/teams', [
            'name' => 'Acme',
        ]);

        $this->assertDatabaseHas('teams', [
            'creator_id' => $user->id,
            'name' => 'Acme',
        ]);

        $team = $user->fresh()->ownedTeams()->first();

        Mail::assertQueued(TeamCreated::class, function (TeamCreated $mail) use ($user, $team) {
            return $mail->hasTo($user->email) &&
                $mail->hasSubject("{$team->name} Team Created!");
        });

        $response->assertRedirect("/teams/{$team->id}");
        $response->assertSessionHas([
            'status' => 'Team created successfully!',
        ]);
    }

    public function test_user_can_create_multiple_teams()
    {
        Mail::fake();

        $user = User::factory()->create();

        $this->actingAs($user);

        $this->post('/teams', [
            'name' => 'Acme',
        ]);

        $this->post('/teams', [
            'name' => 'Acme 2',
        ]);

        $this->assertDatabaseHas('teams', [
            'creator_id' => $user->id,
            'name' => 'Acme',
        ]);

        $this->assertDatabaseHas('teams', [
            'creator_id' => $user->id,
            'name' => 'Acme 2',
        ]);

        $this->assertCount(2, $user->fresh()->ownedTeams);
    }

    public function test_mail_class_contains_correct_data()
    {
        // Create an instance of the TeamCreated Mailable as well as a team and a user
        $mail = new TeamCreated(
            $user = User::factory()->create(),
            $team = Team::factory()->create([
                'name' => 'Acme',
                'creator_id' => $user->id,
            ]));

        $teamMembersURL = config('app.url')."/teams/$team->id/members";

        $mail->assertSeeInOrderInHtml([
            "Hey {$user->username}!",
            "Your new team, {$team->name}, has been created!",
            $teamMembersURL,
            'Invite Members',
        ]);
    }

    public function test_name_must_be_a_string_when_creating_a_team()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/teams', [
            'name' => 1234,
        ]);

        $response->assertSessionHasErrors(['name' => 'Team Name is invalid.']);
    }

    public function test_name_is_required_when_creating_a_team()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/teams', [
            'name' => '',
        ]);

        $response->assertSessionHasErrors(['name' => 'Team Name is required.']);
    }

    public function test_team_name_must_not_be_greater_than_255_characters()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/teams', [
            'name' => Str::random(51),
        ]);

        $response->assertSessionHasErrors(['name' => 'Team Name must not be greater than 50 characters.']);
    }

    public function test_authentication_is_required_to_create_a_team()
    {
        $this->post('/teams', [
            'name' => 'Acme',
        ])->assertRedirect('/login');
    }
}
