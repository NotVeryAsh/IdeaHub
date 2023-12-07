<?php

namespace Teams;

use App\Models\Team;
use App\Models\TeamLink;
use App\Models\User;
use Tests\TestCase;

class JoinTeamLinkTest extends TestCase
{
    public function test_can_join_team_link()
    {
        // Create team creator
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        // Create team for join link user to
        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Create user to click link
        $userTwo = User::factory()->create([
            'email' => 'test2@test.com',
        ]);

        // Create join link
        $teamLink = TeamLink::factory()->create([
            'team_id' => $team->id,
            'token' => 'token',
            'expires_at' => now()->addDay(),
        ]);

        // Authenticate as user two
        $this->actingAs($userTwo);

        // Click join link
        $response = $this->get("/teams/join/$teamLink->token");

        // Assert joined team
        $response->assertRedirect("/teams/$team->id");
        $response->assertSessionHas('status', "You have joined the $team->name team!");
    }

    public function test_joining_link_with_unverified_email_redirects_to_verification_notice_page()
    {
        // Create team creator
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        // Create team for join link user to
        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Create user to click link
        $userTwo = User::factory()->unverified()->create([
            'email' => 'test2@test.com',
        ]);

        // Create join link
        $teamLink = TeamLink::factory()->create([
            'team_id' => $team->id,
            'token' => 'token',
            'expires_at' => now()->addDay(),
        ]);

        // Authenticate as user two
        $this->actingAs($userTwo);

        // Click join link
        $response = $this->get("/teams/join/$teamLink->token");

        $response->assertRedirect('/auth/verify-email');
    }

    public function test_joining_link_redirects_to_login_page_if_not_authenticated()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        // Create team for join link
        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Create join link for team
        $teamLink = TeamLink::factory()->create([
            'team_id' => $team->id,
            'token' => 'token',
            'expires_at' => now()->addDay(),
        ]);

        // Click on join team link
        $response = $this->get("/teams/join/$teamLink->token");

        $response->assertRedirect('/login');
    }

    public function test_join_link_must_not_be_expired()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        // Create team to for join link
        $team = Team::factory()->create([
            'creator_id' => $user->id,
        ]);

        // Create an expired team link
        $teamLink = TeamLink::factory()->create([
            'team_id' => $team->id,
            'expires_at' => now()->subDay(),
        ]);

        // Click on join team link
        $response = $this->get("/teams/join/$teamLink->token");

        $response->assertViewIs('links.invalid');

    }

    public function test_join_link_must_have_a_valid_token()
    {
        // Click on join team link
        $response = $this->get('/teams/join/test');

        $response->assertViewIs('links.invalid');
    }
}
