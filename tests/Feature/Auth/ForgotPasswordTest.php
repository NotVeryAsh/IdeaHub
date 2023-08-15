<?php

namespace Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    public function test_can_send_forgot_password_email()
    {
        self::fakeSuccessfulRecaptchaResponse();

        $user = User::factory()->create(['email' => 'test@example.com']);

        $response = $this->post('/auth/forgot-password', [
            'email' => $user->email,
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas([
            'status' => 'Password reset email sent.',
        ]);
    }

    public function test_reset_password_notification_is_sent()
    {
        self::fakeSuccessfulRecaptchaResponse();

        Notification::fake();

        $user = User::factory()->create(['email' => 'test@example.com']);

        $response = $this->post('/auth/forgot-password', [
            'email' => $user->email,
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        // Check reset password notification was sent to user
        Notification::assertSentTo($user, ResetPasswordNotification::class);

        $response->assertStatus(302);
        $response->assertSessionHas([
            'status' => 'Password reset email sent.',
        ]);
    }

    public function test_password_resets_table_is_populated()
    {
        self::fakeSuccessfulRecaptchaResponse();

        $user = User::factory()->create(['email' => 'test@example.com']);

        $this->post('/auth/forgot-password', [
            'email' => $user->email,
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $user->email,
        ]);
    }

    public function test_logged_in_user_cannot_send_forgot_password_email()
    {
        self::fakeSuccessfulRecaptchaResponse();

        $user = User::factory()->create(['email' => 'test@example.com']);
        $this->actingAs($user);

        $response = $this->post('/auth/forgot-password', [
            'email' => $user->email,
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
    }

    public function test_email_field_is_required_when_sending_forgot_password_email()
    {
        self::fakeSuccessfulRecaptchaResponse();

        $response = $this->post('/auth/forgot-password', [
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'email' => 'Email is required.',
        ]);
    }

    public function test_email_field_must_be_a_valid_email_when_sending_forgot_password_email()
    {
        self::fakeSuccessfulRecaptchaResponse();

        $response = $this->post('/auth/forgot-password', [
            'email' => 'not-an-email',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'email' => 'Email is invalid.',
        ]);
    }

    public function test_email_field_must_exist_in_users_table_when_sending_forgot_password_email()
    {
        self::fakeSuccessfulRecaptchaResponse();

        $response = $this->post('/auth/forgot-password', [
            'email' => 'test@example.com',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'email' => 'Email not found.',
        ]);
    }

    public function test_recaptcha_action_is_required()
    {
        self::fakeSuccessfulRecaptchaResponse();

        $user = User::factory()->create(['email' => 'test@example.com']);

        $response = $this->post('/auth/forgot-password', [
            'email' => $user->email,
            'recaptcha_response' => Str::random(40),
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'recaptcha_action' => 'Recaptcha action is required.',
        ]);
    }

    public function test_recaptcha_action_must_be_string()
    {
        self::fakeSuccessfulRecaptchaResponse();

        $user = User::factory()->create(['email' => 'test@example.com']);

        $response = $this->post('/auth/forgot-password', [
            'email' => $user->email,
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 1,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'recaptcha_action' => 'Recaptcha action is invalid.',
        ]);
    }

    public function test_recaptcha_response_is_required()
    {
        self::fakeSuccessfulRecaptchaResponse();

        $user = User::factory()->create(['email' => 'test@example.com']);

        $response = $this->post('/auth/forgot-password', [
            'email' => $user->email,
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'recaptcha_response' => 'Recaptcha response is required.',
        ]);
    }

    public function test_recaptcha_response_must_be_string()
    {
        self::fakeSuccessfulRecaptchaResponse();

        $user = User::factory()->create(['email' => 'test@example.com']);

        $response = $this->post('/auth/forgot-password', [
            'email' => $user->email,
            'recaptcha_response' => 1,
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'recaptcha_response' => 'Recaptcha response is invalid.',
        ]);
    }

    public function test_recaptcha_response_must_pass()
    {
        self::fakeUnsuccessfulRecaptchaResponse();

        $user = User::factory()->create(['email' => 'test@example.com']);

        $response = $this->post('/auth/forgot-password', [
            'email' => $user->email,
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'recaptcha_response' => 'Recaptcha failed.',
        ]);
    }
}
