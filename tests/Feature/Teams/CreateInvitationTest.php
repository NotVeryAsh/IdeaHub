<?php

namespace Teams;

use App\Mail\Invitations\TeamInvitationSent;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateInvitationTest extends TestCase
{
    public function test_creator_can_create_an_invitation()
    {
        Mail::fake();

        // Fake carbon time so we can compare the expiration date
        Carbon::setTestNow();

        // Create the creator of the team
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $this->actingAs($user);

        // Create team for the invitation
        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Send the invitation
        $response = $this->post("/teams/{$team->id}/invitations", [
            'email' => 'test2@test.com',
        ]);

        // Check that user is redirected to team show page with a success message
        $response->assertRedirectToRoute('teams.show', $team);
        $response->assertSessionHas(['status' => 'Invitation sent!']);

        $this->assertDatabaseHas('team_invitations', [
            'team_id' => $team->id,
            'email' => 'test2@test.com',
            'expires_at' => now()->addWeek(),
        ]);
    }

    public function test_invitation_is_sent_to_user_when_creating_invitation()
    {
        Mail::fake();

        // Create the creator of the team
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $this->actingAs($user);

        // Create team for the invitation
        $team = Team::factory()->create([
            'creator_id' => $user->id,
            'name' => 'Test Testing Test',
        ]);

        // Send email
        $this->post("/teams/{$team->id}/invitations", [
            'email' => 'test2@test.com',
        ]);

        $invitation = TeamInvitation::query()->first();

        Mail::assertQueued(TeamInvitationSent::class, function (TeamInvitationSent $mail) use ($team, $invitation) {
            return $mail->hasTo('test2@test.com')
                && $mail->team->is($team)
                && Str::contains($mail->url, "invitations/$invitation->token");
        });
    }

    public function test_invitation_mailable_contains_correct_data_when_creating_invitation()
    {
        $user = User::factory()->create();

        // Create team for the invitation
        $team = Team::factory()->create([
            'name' => 'Test Testing Test',
            'creator_id' => $user->id,
        ]);

        $mail = new TeamInvitationSent($team, 'https://test.com');

        $mail->assertSeeInOrderInHtml([
            "The {$team->name} team would like you to join them!",
            'By accepting the invitation below, you will be able to collaborate and chat with other members in the team!',
            'But hurry! This invitation will expire in 7 days!',
            'https://test.com',
            'Accept Invitation',
        ]);
    }

    public function test_cannot_invite_existing_team_member_when_creating_invitations()
    {

        // TODO Make this function factor in teams that user is part of but not creator of

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

    public function test_cannot_invite_self_when_creating_invitations()
    {
        Mail::fake();

        // Creator a user for the creator of the team
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $this->actingAs($user);

        // Create team for the invitation
        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Attempt to send invitation to self as the creator
        $response = $this->post("/teams/{$team->id}/invitations", [
            'email' => 'test@test.com',
        ]);

        // Check that user is redirected to profile page
        $response->assertSessionHasErrors(['email' => 'You cannot invite yourself.']);
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

        $response->assertStatus(404);
    }
}
