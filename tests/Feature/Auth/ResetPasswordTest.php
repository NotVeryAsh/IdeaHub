<?php

namespace Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\Concerns\Has;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    public function test_can_reset_password()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        $user = User::factory()->create([
            'password' => 'password',
        ]);

        $token = Password::createToken($user);

        $response = $this->post('auth/reset-password', [
            'token' => $token,
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => $user->email,
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');

        $this->assertTrue(Hash::check('TestPassword', $user->fresh()->password));
    }

    public function test_logged_in_user_cannot_reset_password()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        $user = User::factory()->create();
        $this->actingAs($user);

        $token = Password::createToken($user);

        $response = $this->post('/auth/reset-password', [
            'token' => $token,
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => $user->email,
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
    }

    public function test_password_is_required_when_resetting_password()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $response = $this->post('/auth/reset-password', [
            'token' => 'TestToken',
            'password' => '',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'password' => 'Password is required.',
        ]);
        $this->assertGuest();
    }

    public function test_password_must_be_greater_than_8_characters_when_resetting_password()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $response = $this->post('/auth/reset-password', [
            'token' => 'TestToken',
            'password' => 'a',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'password' => 'Password must be at least 8 characters.',
        ]);
        $this->assertGuest();
    }

    public function test_password_must_be_not_be_greater_than_60_characters_when_resetting_password()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $response = $this->post('/auth/reset-password', [
            'token' => 'TestToken',
            'password' => Str::random(61),
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'password' => 'Password must not be greater than 60 characters.',
        ]);
        $this->assertGuest();
    }

    public function test_password_must_be_confirmed_when_resetting_password()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $response = $this->post('/auth/reset-password', [
            'token' => 'TestToken',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword2',
            'email' => 'test@test.com',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'password' => 'Passwords do not match.',
        ]);
        $this->assertGuest();
    }

    public function test_email_field_is_required_when_sending_forgot_password_email()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        $response = $this->post('/auth/reset-password', [
            'token' => 'TestToken',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
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
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        $response = $this->post('/auth/reset-password', [
            'token' => 'TestToken',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword2',
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
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        $response = $this->post('/auth/reset-password', [
            'token' => 'TestToken',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword2',
            'email' => 'test@example.com',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'email' => 'Email not found.',
        ]);
    }

    public function test_token_is_required_when_resetting_password()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $response = $this->post('/auth/reset-password', [
            'username' => 'username',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'token' => 'Token is required.',
        ]);
    }

    public function test_user_is_remembered_when_remember_me_is_checked()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        $user = User::factory()->create();

        $token = Password::createToken($user);

        $response = $this->post('auth/reset-password', [
            'token' => $token,
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => $user->email,
            'remember' => 'on',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');

        // assert that the user's remember_token has been set
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'remember_token' => null,
        ]);
    }

    public function test_user_is_not_remembered_when_remember_me_is_not_checked()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        $user = User::factory()->create();

        $token = Password::createToken($user);

        $response = $this->post('auth/reset-password', [
            'token' => $token,
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => $user->email,
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');

        // assert that the user's remember_token has not been set
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'remember_token' => null,
        ]);
    }

    public function test_remember_must_be_a_string()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        $user = User::factory()->create();

        $token = Password::createToken($user);

        $response = $this->post('auth/reset-password', [
            'token' => $token,
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => $user->email,
            'remember' => 1,
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'remember' => 'The remember checkbox must be checked or not.',
        ]);
    }

    public function test_checkbox_must_be_passed_as_on()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        $user = User::factory()->create();

        $token = Password::createToken($user);

        $response = $this->post('auth/reset-password', [
            'token' => $token,
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => $user->email,
            'remember' => 'off',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'remember' => 'The remember checkbox must be checked or not.',
        ]);
    }

    public function test_recaptcha_action_is_required()
    {
        self::fakeSuccessfulRecaptchaResponse();

        $user = User::factory()->create([
            'password' => 'password',
        ]);

        $token = Password::createToken($user);

        $response = $this->post('auth/reset-password', [
            'token' => $token,
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
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

        $user = User::factory()->create([
            'password' => 'password',
        ]);

        $token = Password::createToken($user);

        $response = $this->post('auth/reset-password', [
            'token' => $token,
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
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
        self::fakeUnsuccessfulRecaptchaResponse();

        $user = User::factory()->create([
            'password' => 'password',
        ]);

        $token = Password::createToken($user);

        $response = $this->post('auth/reset-password', [
            'token' => $token,
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
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

        $user = User::factory()->create([
            'password' => 'password',
        ]);

        $token = Password::createToken($user);

        $response = $this->post('auth/reset-password', [
            'token' => $token,
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
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

        $user = User::factory()->create([
            'password' => 'password',
        ]);

        $token = Password::createToken($user);

        $response = $this->post('auth/reset-password', [
            'token' => $token,
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
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
