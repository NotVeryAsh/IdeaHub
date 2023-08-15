<?php

namespace Auth;

use App\Models\User;
use Tests\TestCase;

class ForgotPasswordViewTest extends TestCase
{
    public function test_forgot_password_route_returns_correct_view()
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
        $response->assertViewIs('auth.forgot-password');
    }

    public function test_forgot_password_view_returns_correct_data()
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
        $response->assertSee('Forgot Password');
    }

    public function test_authenticated_user_is_redirected_to_dashboard()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/forgot-password');

        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
    }

    public function test_recaptcha_data_is_present()
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
        $response->assertSeeInOrder([
            'recaptcha-protected-form',
            'data-sitekey',
            'data-action',
        ]);
    }
}
