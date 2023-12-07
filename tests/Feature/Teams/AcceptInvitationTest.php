<?php

namespace Teams;

use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\TeamUser;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AcceptInvitationTest extends TestCase
{
    public function test_can_accept_team_invitation()
    {
        // Create team creator
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        // Create team to invite user to
        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Create user to invite
        $userTwo = User::factory()->create([
            'email' => 'test2@test.com',
        ]);

        // Create invitation for user two
        $teamInvitation = TeamInvitation::factory()->create([
            'team_id' => $team->id,
            'email' => $userTwo->email,
        ]);

        // Create a signed url for the invitation
        $url = URL::temporarySignedRoute('invitations.accept', $teamInvitation->expires_at, ['token' => $teamInvitation->token]);

        // Authenticate as user two
        $this->actingAs($userTwo);

        // Accept invitation
        $response = $this->get($url);

        // Assert invitation is accepted
        $response->assertRedirect("/teams/$team->id");
        $response->assertSessionHas('status', "You have joined the $team->name team!");
    }

    public function test_accepting_invitation_with_unverified_email_redirects_to_verify_email_page()
    {
        // Create team creator
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        // Create team to invite user to
        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Create user to invite
        $userTwo = User::factory()->unverified()->create([
            'email' => 'test2@test.com',
        ]);

        // Create invitation for user two
        $teamInvitation = TeamInvitation::factory()->create([
            'team_id' => $team->id,
            'email' => $userTwo->email,
        ]);

        // Create a signed url for the invitation
        $url = URL::temporarySignedRoute('invitations.accept', $teamInvitation->expires_at, ['token' => $teamInvitation->token]);

        // Authenticate as user two
        $this->actingAs($userTwo);

        // Accept invitation
        $response = $this->get($url);

        $response->assertRedirect('/auth/verify-email');
    }

    public function test_accept_invitation_redirects_to_login_page_if_email_already_exists_when_not_authenticated()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        // Create team to invite user to
        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Create user to invite
        $userTwo = User::factory()->create([
            'email' => 'test2@test.com',
        ]);

        // Create invitation for user two
        $teamInvitation = TeamInvitation::factory()->create([
            'team_id' => $team->id,
            'email' => $userTwo->email,
        ]);

        // Create a signed url for the invitation
        $url = URL::temporarySignedRoute('invitations.accept', $teamInvitation->expires_at, ['token' => $teamInvitation->token]);

        // Accept invitation
        $response = $this->get($url);

        $response->assertRedirect("/login?token=$teamInvitation->token");
    }

    public function test_accept_invitation_redirects_to_signup_page_if_email_already_exists_when_not_authenticated()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        // Create team to invite user to
        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Create invitation for user two
        $teamInvitation = TeamInvitation::factory()->create([
            'team_id' => $team->id,
            'email' => 'test2@test.com',
        ]);

        // Create a signed url for the invitation
        $url = URL::temporarySignedRoute('invitations.accept', $teamInvitation->expires_at, ['token' => $teamInvitation->token]);

        // Accept invitation
        $response = $this->get($url);

        $response->assertRedirect("/register?token=$teamInvitation->token");
    }

    public function test_invitation_must_not_be_expired_when_accepting_invitations()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        // Create team to invite user to
        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Create an expired invitation
        $teamInvitation = TeamInvitation::factory()->create([
            'team_id' => $team->id,
            'email' => 'test2@test.com',
            'expires_at' => now()->subDay(),
        ]);

        // Create a signed url for the invitation
        $url = URL::temporarySignedRoute('invitations.accept', $teamInvitation->expires_at, ['token' => $teamInvitation->token]);

        // Accept invitation
        $response = $this->get($url);

        $response->assertViewIs('invitations.invalid');

    }

    public function test_cannot_accept_invitation_for_other_users()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        // Create team to invite user to
        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Create a user for the invitation
        $userTwo = User::factory()->create([
            'email' => 'test2@test.com',
        ]);

        // Create an invitation for the second user
        $teamInvitation = TeamInvitation::factory()->create([
            'team_id' => $team->id,
            'email' => $userTwo->email,
        ]);

        // Create a signed url for the invitation
        $url = URL::temporarySignedRoute('invitations.accept', $teamInvitation->expires_at, ['token' => $teamInvitation->token]);

        // Create another user to attempt to accept the invitation
        $userThree = User::factory()->create([
            'email' => 'test3@test.com',
        ]);

        // Attempt to accept the invitation as the wrong user
        $this->actingAs($userThree);
        $response = $this->get($url);

        $response->assertViewIs('invitations.invalid');
    }

    public function test_invitation_token_must_be_valid_when_accepting_invitation()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        // Create team to invite user to
        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Create an expired invitation
        $teamInvitation = TeamInvitation::factory()->create([
            'team_id' => $team->id,
            'email' => 'test2@test.com',
            'token' => 'token',
        ]);

        // Create a signed url for an invalid invitation token
        $url = URL::temporarySignedRoute('invitations.accept', $teamInvitation->expires_at, ['token' => 'invalid-token']);

        // Accept invitation
        $response = $this->get($url);

        $response->assertViewIs('invitations.invalid');
    }

    public function test_cannot_accept_invitation_if_user_is_already_in_account()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        // Create second user for the team
        $userTwo = User::factory()->create([
            'email' => 'test2@test.com',
        ]);

        // Create team to invite user to
        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Add user to team
        TeamUser::factory()->create([
            'team_id' => $team->id,
            'user_id' => $userTwo->id,
        ]);

        // Create an invitation for the user that is already in the account
        $teamInvitation = TeamInvitation::factory()->create([
            'team_id' => $team->id,
            'email' => $userTwo->email,
        ]);

        // Create a signed url for the invitation
        $url = URL::temporarySignedRoute('invitations.accept', $teamInvitation->expires_at, ['token' => $teamInvitation->token]);

        // Authenticate as user that was invited
        $this->actingAs($userTwo);

        // Accept invitation
        $response = $this->get($url);

        $response->assertRedirect("/teams/$team->id");
        $response->assertSessionHas('status', "You have joined the $team->name team!");
    }

    public function test_invitation_is_deleted_when_accepting_invitation()
    {
        // Create team creator
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        // Create team to invite user to
        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Create user to invite
        $userTwo = User::factory()->create([
            'email' => 'test2@test.com',
        ]);

        // Create invitation for user two
        $teamInvitation = TeamInvitation::factory()->create([
            'team_id' => $team->id,
            'email' => $userTwo->email,
        ]);

        // Create a signed url for the invitation
        $url = URL::temporarySignedRoute('invitations.accept', $teamInvitation->expires_at, ['token' => $teamInvitation->token]);

        // Authenticate as user two
        $this->actingAs($userTwo);

        // Accept invitation
        $this->get($url);

        // Assert invitation is accepted
        $this->assertDatabaseMissing('team_invitations', [
            'id' => $teamInvitation->id,
        ]);
    }

    public function test_email_field_is_populated_on_signup_page_when_accepting_invitation()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        // Create team to invite user to
        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Create invitation for user two
        $teamInvitation = TeamInvitation::factory()->create([
            'team_id' => $team->id,
            'email' => 'test2@test.com',
        ]);

        // Go to register page with invitation token
        $response = $this->get("/register?token=$teamInvitation->token");

        $response->assertSeeInOrder([
            'Sign Up',
            'test2@test.com',
        ]);
    }

    public function test_email_field_is_populated_on_login_page_when_accepting_invitation()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        // Create team to invite user to
        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Create a second user
        User::factory()->create([
            'email' => 'test2@test.com',
        ]);

        // Create invitation for user two
        $teamInvitation = TeamInvitation::factory()->create([
            'team_id' => $team->id,
            'email' => 'test2@test.com',
        ]);

        // Go to register page with invitation token
        $response = $this->get("/login?token=$teamInvitation->token");

        $response->assertSeeInOrder([
            'Log In',
            'test2@test.com',
        ]);
    }
}
