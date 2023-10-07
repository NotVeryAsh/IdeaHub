<?php

namespace Teams;

use App\Models\Team;
use App\Models\TeamInvitation;
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
        $response->assertSessionHas('success', "You have joined the $team->name team!");
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

        $response->assertRedirect('/login');
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

        $response->assertRedirect('/signup');
    }

    public function test_invitation_must_not_be_expired_when_accepting_invitations()
    {

    }

    public function test_cannot_accept_invitation_for_other_users()
    {

    }

    public function test_invitation_token_must_be_valid_when_accepting_invitation()
    {

    }

    public function test_cannot_accept_invitation_if_user_is_already_in_account()
    {

    }

    public function test_user_is_redirected_to_team_page_when_accepting_invitation_and_already_authenticated()
    {

    }

    public function test_invitation_is_deleted_when_accepting_invitation()
    {

    }

    public function test_email_field_is_populated_on_signup_page_when_accepting_invitation()
    {

    }

    public function test_email_field_is_populated_on_login_page_when_accepting_invitation()
    {

    }
}
