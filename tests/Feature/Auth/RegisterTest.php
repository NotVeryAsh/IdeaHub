<?php

namespace Tests\Feature\Auth;

use App\Mail\RegisteredUser;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function test_can_register()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        Notification::fake();
        Mail::fake();

        $response = $this->post('/auth/register', [
            'username' => 'username',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/auth/verify-email');

        $user = User::query()->first();
        $this->assertAuthenticatedAs($user);

        // Check if the user was created in the database
        $this->assertDatabaseHas('users', [
            'username' => 'username',
            'email' => 'test@test.com',
        ]);

        $user = User::query()->where('username', 'username')->first();

        Notification::assertSentTo($user, VerifyEmail::class);
        Mail::assertQueued(RegisteredUser::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email)
                && $mail->user->is($user)
                && $mail->hasFrom('info@idea-hub.net', 'Idea Hub');
        });
    }

    public function test_username_is_required_when_registering()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        $response = $this->post('/auth/register', [
            'username' => '',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'username' => 'Username is required.',
        ]);
        $this->assertGuest();
    }

    public function test_username_must_be_unique_when_registering()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        User::factory()->create([
            'username' => 'username',
        ]);

        $response = $this->post('/auth/register', [
            'username' => 'username',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'username' => 'Username has already been taken.',
        ]);
        $this->assertGuest();
    }

    public function test_username_must_be_greater_than_3_characters_when_registering()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        $response = $this->post('/auth/register', [
            'username' => 'a',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'username' => 'Username must be at least 3 characters.',
        ]);
        $this->assertGuest();
    }

    public function test_username_must_be_not_be_greater_than_20_characters_when_registering()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        $response = $this->post('/auth/register', [
            'username' => Str::random(21),
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'username' => 'Username must not be greater than 20 characters.',
        ]);
        $this->assertGuest();
    }

    public function test_username_must_comply_with_alpha_dash_rule_when_registering()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        $response = $this->post('/auth/register', [
            'username' => 'username!',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'username' => 'Username must only contain letters, numbers, dashes, and underscores.',
        ]);
        $this->assertGuest();
    }

    public function test_password_is_required_when_registering()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        $response = $this->post('/auth/register', [
            'username' => 'username',
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

    public function test_password_must_be_greater_than_8_characters_when_registering()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        $response = $this->post('/auth/register', [
            'username' => 'username',
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

    public function test_password_must_be_not_be_greater_than_60_characters_when_registering()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        $response = $this->post('/auth/register', [
            'username' => 'username',
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

    public function test_password_must_be_confirmed_when_registering()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        $response = $this->post('/auth/register', [
            'username' => 'username',
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

    public function test_email_is_required_when_registering()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        $response = $this->post('/auth/register', [
            'username' => 'username',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => '',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'email' => 'Email is required.',
        ]);
        $this->assertGuest();
    }

    public function test_email_must_be_valid_when_registering()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        $response = $this->post('/auth/register', [
            'username' => 'username',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'email',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'email' => 'Email must be a valid email address.',
        ]);
        $this->assertGuest();
    }

    public function test_email_must_be_unique_when_registering()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $response = $this->post('/auth/register', [
            'username' => 'username',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'email' => 'Email has already been taken.',
        ]);
        $this->assertGuest();
    }

    public function test_email_must_be_not_be_greater_than_255_characters_when_registering()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        $response = $this->post('/auth/register', [
            'username' => 'username',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => Str::random(256).'@example.com',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'email' => 'Email must not be greater than 255 characters.',
        ]);
        $this->assertGuest();
    }

    public function test_user_is_remembered_when_remember_me_is_checked()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        Notification::fake();
        Mail::fake();

        $response = $this->post('/auth/register', [
            'username' => 'username',
            'password' => 'password',
            'password_confirmation' => 'password',
            'email' => 'test@test.com',
            'remember' => 'on',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/auth/verify-email');

        $user = User::query()->first();
        $this->assertAuthenticatedAs($user);

        // Check if the user was created in the database
        $this->assertDatabaseHas('users', [
            'username' => 'username',
            'email' => 'test@test.com',
        ]);

        $user = User::query()->where('username', 'username')->first();

        Notification::assertSentTo($user, VerifyEmail::class);
        Mail::assertQueued(RegisteredUser::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email)
                && $mail->user->is($user)
                && $mail->hasSubject("Welcome to Idea Hub, $user->username!");
        });
    }

    public function test_user_is_not_remembered_when_remember_me_is_not_checked()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        Notification::fake();
        Mail::fake();

        $response = $this->post('/auth/register', [
            'username' => 'username',
            'password' => 'password',
            'password_confirmation' => 'password',
            'email' => 'test@test.com',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/auth/verify-email');

        $user = User::query()->first();
        $this->assertAuthenticatedAs($user);

        // Check if the user was created in the database
        $this->assertDatabaseHas('users', [
            'username' => 'username',
            'email' => 'test@test.com',
        ]);

        $user = User::query()->where('username', 'username')->first();

        Notification::assertSentTo($user, VerifyEmail::class);
        Mail::assertQueued(RegisteredUser::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email)
                && $mail->user->is($user)
                && $mail->hasSubject("Welcome to Idea Hub, $user->username!");
        });
    }

    public function test_remember_must_be_a_string()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        $response = $this->post('/auth/register', [
            'username' => 'username',
            'password' => 'password',
            'password_confirmation' => 'password',
            'email' => 'test@test.com',
            'remember' => 1,
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'remember' => 'Remember checkbox must be checked or not.',
        ]);
    }

    public function test_checkbox_must_be_passed_as_on()
    {
        // Fake google recaptcha response
        self::fakeSuccessfulRecaptchaResponse();

        $response = $this->post('/auth/register', [
            'username' => 'username',
            'password' => 'password',
            'password_confirmation' => 'password',
            'email' => 'test@test.com',
            'remember' => 'off',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'remember' => 'Remember checkbox must be checked or not.',
        ]);
    }

    public function test_recaptcha_action_is_required()
    {
        self::fakeSuccessfulRecaptchaResponse();

        $response = $this->post('/auth/register', [
            'username' => 'username',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
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

        $response = $this->post('/auth/register', [
            'username' => 'username',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 1,
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

        $response = $this->post('/auth/register', [
            'username' => 'username',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
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

        $response = $this->post('/auth/register', [
            'username' => 'username',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
            'recaptcha_response' => 1,
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

        $response = $this->post('/auth/register', [
            'username' => 'username',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
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
