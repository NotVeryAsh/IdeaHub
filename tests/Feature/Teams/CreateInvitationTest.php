<?php

namespace Teams;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CreateInvitationTest extends TestCase
{
    public function test_creator_can_create_an_invitation()
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $this->actingAs($user);

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        $response = $this->post("/teams/{$team->id}/invitations", [
            'email' => 'test2@test.com',
        ]);

        $this->assertDatabaseHas('invitations', [
            'team_id' => $team->id,
            'email' => 'test2@test.com',
        ]);
    }

    public function test_random_uuid_is_generated_when_creating_an_invitation()
    {

    }

    public function test_expiration_date_is_generated_when_creating_an_invitation()
    {

    }

    public function test_email_is_required_to_create_an_invitation()
    {
    }

    public function test_emails_must_be_valid_to_create_an_invitation()
    {
    }

    public function test_email_must_not_be_greater_than_255_characters_to_create_an_invitation()
    {
    }

    public function test_authentication_is_required_to_create_an_invitation()
    {
    }

    public function test_creator_authorization_is_required_to_create_an_invitation()
    {
        // Only creators of teams can create invitations
    }
}
