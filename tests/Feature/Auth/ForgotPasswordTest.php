<?php

namespace Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    public function test_can_send_forgot_password_email()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);

        $this->post('/auth/forgot-password', [
            'email' => $user->email,
        ]);
    }

    public function test_reset_password_notification_is_sent()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);

        $this->post('/auth/forgot-password', [
            'email' => $user->email,
        ]);

        Notification::assertSentTo($user, ResetPasswordNotification::class);
    }

    public function test_password_resets_table_is_populated()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);

        $this->post('/auth/forgot-password', [
            'email' => $user->email,
        ]);

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $user->email,
        ]);
    }

    public function test_logged_in_user_cannot_send_forgot_password_email()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);
        $this->actingAs($user);

        $response = $this->post('/auth/forgot-password', [
            'email' => $user->email,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
    }

    public function test_email_field_is_required_when_sending_forgot_password_email()
    {
        $response = $this->post('/auth/forgot-password');

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'email' => 'Email is required.',
        ]);
    }

    public function test_email_field_must_be_a_valid_email_when_sending_forgot_password_email()
    {
        $response = $this->post('/auth/forgot-password', [
            'email' => 'not-an-email',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'email' => 'Email is invalid.',
        ]);
    }

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
}
