<?php

namespace Auth;

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ResetPasswordViewTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_logged_in_user_cannot_see_reset_password_view()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/reset-password/fake-token');

        $response->assertStatus(302);
    }

    public function test_password_reset_view_is_returned()
    {
        $user = User::factory()->create();

        $token = Password::createToken($user);

        $response = $this->get("reset-password/$token");
        $response->assertStatus(200);
        $response->assertViewIs('auth.reset-password');
    }

    public function test_password_reset_view_returns_correct_data()
    {
        $user = User::factory()->create();

        $token = Password::createToken($user);

        $response = $this->get("reset-password/$token");
        $response->assertStatus(200);
        $response->assertSeeInOrder(['Reset Password', $token]);
    }

    public function test_recaptcha_data_is_present()
    {
        $user = User::factory()->create();

        $token = Password::createToken($user);

        $response = $this->get("reset-password/$token");
        $response->assertSeeInOrder([
            'recaptcha-protected-form',
            'data-sitekey',
            'data-action',
        ]);
    }
}
