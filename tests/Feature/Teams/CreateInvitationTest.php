<?php

namespace Teams;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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

        // Check that user is redirected to profile page
        $response->assertRedirectToRoute('teams.show', $team);
        $response->assertSessionHas(['status' => 'Invitation sent!']);
    }

    public function test_invitation_is_sent_to_user_when_creating_invitation()
    {
        Mail::fake();
    }

    public function test_invitation_mailable_contains_correct_data_when_creating_invitation()
    {
        //$mail = new TeamInvitationSent($team, $signedURL);
    }

    public function test_cannot_invite_existing_team_member_when_creating_invitations()
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

        // Check that user is redirected to profile page
        $response->assertRedirectToRoute('teams.show', $team);
        $response->assertSessionHas(['status' => 'Invitation sent!']);
    }

    public function test_signed_url_is_generated_when_creating_invitation()
    {

    }

    public function test_signed_url_is_invalidated_after_one_week()
    {

    }

    public function test_email_is_required_to_create_an_invitation()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $this->actingAs($user);

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        $response = $this->post("/teams/{$team->id}/invitations", [
            'email' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'email' => 'Email is required.',
        ]);
    }

    public function test_emails_must_be_valid_to_create_an_invitation()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $this->actingAs($user);

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        $response = $this->post("/teams/{$team->id}/invitations", [
            'email' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'email' => 'Email is invalid.',
        ]);
    }

    public function test_email_must_not_be_greater_than_255_characters_to_create_an_invitation()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $this->actingAs($user);

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        $response = $this->post("/teams/{$team->id}/invitations", [
            'email' => Str::random(256),
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'email' => 'Email must not be greater than 255 characters.',
        ]);
    }

    public function test_authentication_is_required_to_create_an_invitation()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $this->actingAs($user);

        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        $response = $this->post("/teams/{$team->id}/invitations", [
            'email' => 'test@test.com',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_creator_authorization_is_required_to_create_an_invitation()
    {
        // Create the creator of the team
        $creator = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        // Create another user
        $user = User::factory()->create([
            'email' => 'test2@test.com',
        ]);

        // Act as the user
        $this->actingAs($user);

        // Create a team for the creator
        $team = Team::factory()->create([
            'creator_id' => $creator->id,
        ]);

        // Attempt to create an invitation for the team which the user does not have permission for
        $response = $this->post("/teams/{$team->id}/invitations", [
            'email' => 'test3@test.com',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
}
