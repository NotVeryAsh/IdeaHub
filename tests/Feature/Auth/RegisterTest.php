<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function test_can_register()
    {
        Notification::fake();

        $response = $this->postJson('/auth/register', [
            'username' => 'username',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/auth/verify-email');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'username' => 'username',
            'email' => 'test@test.com',
        ]);

        $user = User::query()->where('username', 'username')->first();

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_username_is_required_when_registering()
    {
        $response = $this->postJson('/auth/register', [
            'username' => '',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'username' => 'The username field is required.',
        ]);
        $this->assertGuest();
    }

    public function test_username_must_be_unique_when_registering()
    {
        User::factory()->create([
            'username' => 'username',
        ]);

        $response = $this->postJson('/auth/register', [
            'username' => 'username',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'username' => 'The username has already been taken.',
        ]);
        $this->assertGuest();
    }

    public function test_username_must_be_greater_than_3_characters_when_registering()
    {
        $response = $this->postJson('/auth/register', [
            'username' => 'a',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'username' => 'The username must be between 3 and 20 characters.',
        ]);
        $this->assertGuest();
    }

    public function test_username_must_be_not_be_greater_than_20_characters_when_registering()
    {
        $response = $this->postJson('/auth/register', [
            'username' => Str::random(21),
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'username' => 'The username must not be greater than 20 characters.',
        ]);
        $this->assertGuest();
    }

    public function test_username_must_comply_with_alpha_dash_rule_when_registering()
    {
        $response = $this->postJson('/auth/register', [
            'username' => 'username!',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'username' => 'The username may only contain letters, numbers, dashes and underscores.',
        ]);
        $this->assertGuest();
    }

    public function test_password_is_required_when_registering()
    {
        $response = $this->postJson('/auth/register', [
            'username' => 'username',
            'password' => '',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'password' => 'The password field is required.',
        ]);
        $this->assertGuest();
    }

    public function test_password_must_be_greater_than_8_characters_when_registering()
    {
        $response = $this->postJson('/auth/register', [
            'username' => 'username',
            'password' => 'a',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'password' => 'The password must be at least 8 characters.',
        ]);
        $this->assertGuest();
    }

    public function test_password_must_be_not_be_greater_than_60_characters_when_registering()
    {
        $response = $this->postJson('/auth/register', [
            'username' => 'username',
            'password' => Str::random(61),
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'password' => 'The password must not be greater than 60 characters.',
        ]);
        $this->assertGuest();
    }

    public function test_password_must_comply_with_password_rule_when_registering()
    {
        $response = $this->postJson('/auth/register', [
            'username' => 'username',
            'password' => 'password',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'password' => 'The password format is invalid.',
        ]);
        $this->assertGuest();
    }

    public function test_password_must_be_confirmed_when_registering()
    {
        $response = $this->postJson('/auth/register', [
            'username' => 'username',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword2',
            'email' => 'test@test.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'password' => 'The password confirmation does not match.',
        ]);
        $this->assertGuest();
    }

    public function test_email_is_required_when_registering()
    {
        $response = $this->postJson('/auth/register', [
            'username' => 'username',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'email' => 'The email field is required.',
        ]);
        $this->assertGuest();
    }

    public function test_email_must_be_valid_when_registering()
    {
        $response = $this->postJson('/auth/register', [
            'username' => 'username',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'email',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'email' => 'The email must be a valid email address.',
        ]);
        $this->assertGuest();
    }

    public function test_email_must_be_unique_when_registering()
    {
        User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $response = $this->postJson('/auth/register', [
            'username' => 'username',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'email' => 'The email has already been taken.',
        ]);
        $this->assertGuest();
    }

    public function test_email_must_be_not_be_greater_than_255_characters_when_registering()
    {
        $response = $this->postJson('/auth/register', [
            'username' => 'username',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => $this->faker->email.Str::random(256),
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'email' => 'The email must not be greater than 255 characters.',
        ]);
        $this->assertGuest();
    }
}
