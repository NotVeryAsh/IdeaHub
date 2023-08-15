<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_user_can_login_with_valid_email()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        $user = User::factory()->create([
            'email' => 'test@test.com',
            'password' => Hash::make($password = 'password'),
        ]);

        $response = $this->post('/auth/login', [
            'identifier' => 'test@test.com',
            'password' => $password,
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_login_with_valid_username()
    {
        self::fakeSuccessfulRecaptchaResponse();

        $user = User::factory()->create([
            'password' => Hash::make($password = 'password'),
        ]);

        $response = $this->post('/auth/login', [
            'identifier' => $user->username,
            'password' => $password,
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_is_remembered_when_remember_me_is_checked()
    {
        self::fakeSuccessfulRecaptchaResponse();

        $user = User::factory()->create([
            'password' => Hash::make($password = 'password'),
            'remember_token' => null,
        ]);

        $response = $this->post('/auth/login', [
            'identifier' => $user->username,
            'password' => $password,
            'remember' => 'on',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('dashboard');
        $this->assertAuthenticatedAs($user);

        // assert that the user's remember_token has been set
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'remember_token' => null,
        ]);
    }

    public function test_user_is_not_remembered_when_remember_me_is_not_checked()
    {
        self::fakeSuccessfulRecaptchaResponse();

        $user = User::factory()->create([
            'password' => Hash::make($password = 'password'),
            'remember_token' => null,
        ]);

        $response = $this->post('/auth/login', [
            'identifier' => $user->username,
            'password' => $password,
            'remember' => null,
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('dashboard');
        $this->assertAuthenticatedAs($user);

        // assert that the user's remember_token has not been set
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'remember_token' => null,
        ]);
    }

    public function test_remember_must_be_a_string()
    {
        self::fakeSuccessfulRecaptchaResponse();

        $user = User::factory()->create([
            'password' => Hash::make($password = 'password'),
            'remember_token' => null,
        ]);

        $response = $this->post('/auth/login', [
            'identifier' => $user->username,
            'password' => $password,
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
        self::fakeSuccessfulRecaptchaResponse();

        $user = User::factory()->create([
            'password' => Hash::make($password = 'password'),
            'remember_token' => null,
        ]);

        $response = $this->post('/auth/login', [
            'identifier' => $user->username,
            'password' => $password,
            'remember' => 'off',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'remember' => 'The remember checkbox must be checked or not.',
        ]);
    }

    public function test_login_fails_when_invalid_email_is_provided()
    {
        self::fakeSuccessfulRecaptchaResponse();

        $response = $this->post('/auth/login', [
            'identifier' => 'test@test.com',
            'password' => 'invalid-password',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'identifier' => 'Email or username is incorrect.',
        ]);
        $this->assertGuest();
    }

    public function test_login_fails_when_invalid_username_is_provided()
    {
        self::fakeSuccessfulRecaptchaResponse();

        $response = $this->post('/auth/login', [
            'identifier' => 'test',
            'password' => 'invalid-password',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'identifier' => 'Email or username is incorrect.',
        ]);
        $this->assertGuest();
    }

    public function test_login_fails_when_invalid_password_is_provided()
    {
        self::fakeSuccessfulRecaptchaResponse();

        User::factory()->create([
            'email' => 'test@test.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/auth/login', [
            'identifier' => 'test@test.com',
            'password' => 'test_password',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'password' => 'Password is incorrect. Try again or click "Forgot Password" to reset your password.',
        ]);
        $this->assertGuest();
    }

    public function test_identifier_field_is_required()
    {
        self::fakeSuccessfulRecaptchaResponse();

        $response = $this->post('/auth/login', [
            'password' => 'test',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'identifier' => 'Email or username is required.',
        ]);
        $this->assertGuest();
    }

    public function test_password_field_is_required()
    {
        self::fakeSuccessfulRecaptchaResponse();

        User::factory()->create(['username' => 'test']);

        $response = $this->post('/auth/login', [
            'identifier' => 'test',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'password' => 'Password is required.',
        ]);

        $this->assertGuest();
    }

    public function test_identifier_must_not_be_greater_than_255_characters()
    {
        self::fakeSuccessfulRecaptchaResponse();

        $response = $this->post('/auth/login', [
            'identifier' => Str::random(256),
            'password' => 'test',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'identifier' => 'Email or username is incorrect.',
        ]);
        $this->assertGuest();
    }

    public function test_password_must_not_be_greater_than_60_characters()
    {
        self::fakeSuccessfulRecaptchaResponse();

        $response = $this->post('/auth/login', [
            'identifier' => 'test',
            'password' => Str::random(61),
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'password' => 'Password is incorrect. Try again or click "Forgot Password" to reset your password.',
        ]);
        $this->assertGuest();
    }

    public function test_identifier_must_be_a_string()
    {
        self::fakeSuccessfulRecaptchaResponse();

        $response = $this->post('/auth/login', [
            'identifier' => 123,
            'password' => 'test',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'identifier' => 'Email or username is incorrect.',
        ]);
        $this->assertGuest();
    }

    public function test_password_must_be_a_string()
    {
        self::fakeSuccessfulRecaptchaResponse();

        $response = $this->post('/auth/login', [
            'identifier' => 'test',
            'password' => 123,
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'password' => 'Password is incorrect. Try again or click "Forgot Password" to reset your password.',
        ]);
        $this->assertGuest();
    }

    public function test_recaptcha_action_is_required()
    {
        self::fakeSuccessfulRecaptchaResponse();

        User::factory()->create([
            'email' => 'test@test.com',
            'password' => Hash::make($password = 'password'),
        ]);

        $response = $this->post('/auth/login', [
            'identifier' => 'test@test.com',
            'password' => $password,
            'recaptcha_response' => Str::random(40),
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'recaptcha_action' => 'Recaptcha action is required.',
        ]);
        $this->assertGuest();
    }

    public function test_recaptcha_action_must_be_string()
    {
        self::fakeSuccessfulRecaptchaResponse();

        User::factory()->create([
            'email' => 'test@test.com',
            'password' => Hash::make($password = 'password'),
        ]);

        $response = $this->post('/auth/login', [
            'identifier' => 'test@test.com',
            'password' => $password,
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 0,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'recaptcha_action' => 'Recaptcha action is invalid.',
        ]);
        $this->assertGuest();
    }

    public function test_recaptcha_response_is_required()
    {
        self::fakeUnsuccessfulRecaptchaResponse();

        User::factory()->create([
            'email' => 'test@test.com',
            'password' => Hash::make($password = 'password'),
        ]);

        $response = $this->post('/auth/login', [
            'identifier' => 'test@test.com',
            'password' => $password,
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'recaptcha_response' => 'Recaptcha response is required.',
        ]);
        $this->assertGuest();
    }

    public function test_recaptcha_response_must_be_string()
    {
        self::fakeSuccessfulRecaptchaResponse();

        User::factory()->create([
            'email' => 'test@test.com',
            'password' => Hash::make($password = 'password'),
        ]);

        $response = $this->post('/auth/login', [
            'identifier' => 'test@test.com',
            'password' => $password,
            'recaptcha_response' => 0,
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'recaptcha_response' => 'Recaptcha response is invalid.',
        ]);
        $this->assertGuest();
    }

    public function test_recaptcha_response_must_pass()
    {
        self::fakeUnsuccessfulRecaptchaResponse();

        User::factory()->create([
            'email' => 'test@test.com',
            'password' => Hash::make($password = 'password'),
        ]);

        $response = $this->post('/auth/login', [
            'identifier' => 'test@test.com',
            'password' => $password,
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'recaptcha_response' => 'Recaptcha failed.',
        ]);
        $this->assertGuest();
    }
}
