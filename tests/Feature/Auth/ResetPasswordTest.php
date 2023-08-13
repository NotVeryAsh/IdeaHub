<?php

namespace Auth;

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    public function test_can_reset_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('old-password'),
        ]);

        $token = Password::createToken($user);

        $response = $this->post('auth/reset-password', [
            'token' => $token,
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => $user->email,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');

        $this->assertDatabaseHas('users', [
            'email' => $user->email,
            'password' => bcrypt('TestPassword'),
        ]);
    }

    public function test_logged_in_user_cannot_reset_password()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $token = Password::createToken($user);

        $response = $this->post('/auth/reset-password', [
            'token' => $token,
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => $user->email,
        ]);

        $response->assertStatus(302);
    }

    public function test_password_is_required_when_resetting_password()
    {
        User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $response = $this->post('/auth/reset-password', [
            'token' => 'TestToken',
            'password' => '',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'password' => 'Password is required.',
        ]);
        $this->assertGuest();
    }

    public function test_password_must_be_greater_than_8_characters_when_resetting_password()
    {
        User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $response = $this->post('/auth/reset-password', [
            'token' => 'TestToken',
            'password' => 'a',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'password' => 'Password must be at least 8 characters.',
        ]);
        $this->assertGuest();
    }

    public function test_password_must_be_not_be_greater_than_60_characters_when_resetting_password()
    {
        User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $response = $this->post('/auth/reset-password', [
            'token' => 'TestToken',
            'password' => Str::random(61),
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'password' => 'Password must not be greater than 60 characters.',
        ]);
        $this->assertGuest();
    }

    public function test_password_must_be_confirmed_when_resetting_password()
    {
        User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $response = $this->post('/auth/reset-password', [
            'token' => 'TestToken',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword2',
            'email' => 'test@test.com',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'password' => 'Passwords do not match.',
        ]);
        $this->assertGuest();
    }

    public function test_email_field_is_required_when_sending_forgot_password_email()
    {
        $response = $this->post('/auth/reset-password', [
            'token' => 'TestToken',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'email' => 'Email is required.',
        ]);
    }

    public function test_email_field_must_be_a_valid_email_when_sending_forgot_password_email()
    {
        $response = $this->post('/auth/reset-password', [
            'token' => 'TestToken',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword2',
            'email' => 'not-an-email',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'email' => 'Email is invalid.',
        ]);
    }

    public function test_email_field_must_exist_in_users_table_when_sending_forgot_password_email()
    {
        $response = $this->post('/auth/reset-password', [
            'token' => 'TestToken',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword2',
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'email' => 'Email not found.',
        ]);
    }

    public function test_token_is_required_when_resetting_password()
    {
        User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $response = $this->post('/auth/reset-password', [
            'username' => 'username',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'token' => 'Token is required.',
        ]);
    }
}
