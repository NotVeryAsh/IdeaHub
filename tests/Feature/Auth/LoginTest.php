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
        $user = User::factory()->create([
            'email' => 'test@test.com',
            'password' => Hash::make($password = 'password'),
        ]);

        $response = $this->post('/auth/login', [
            'identifier' => 'test@test.com',
            'password' => $password,
        ]);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_login_with_valid_username()
    {
        $user = User::factory()->create([
            'password' => Hash::make($password = 'password'),
        ]);

        $response = $this->post('/auth/login', [
            'identifier' => $user->username,
            'password' => $password,
        ]);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_is_remembered_when_remember_me_is_checked()
    {
        $user = User::factory()->create([
            'password' => Hash::make($password = 'password'),
            'remember_token' => null,
        ]);

        $response = $this->post('/auth/login', [
            'identifier' => $user->username,
            'password' => $password,
            'remember' => 'on',
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
        $user = User::factory()->create([
            'password' => Hash::make($password = 'password'),
            'remember_token' => null,
        ]);

        $response = $this->post('/auth/login', [
            'identifier' => $user->username,
            'password' => $password,
            'remember' => null,
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
        $user = User::factory()->create([
            'password' => Hash::make($password = 'password'),
            'remember_token' => null,
        ]);

        $response = $this->post('/auth/login', [
            'identifier' => $user->username,
            'password' => $password,
            'remember' => 1,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'remember' => 'The remember checkbox must be checked or not.',
        ]);
    }

    public function test_checkbox_must_be_passed_as_on()
    {
        $user = User::factory()->create([
            'password' => Hash::make($password = 'password'),
            'remember_token' => null,
        ]);

        $response = $this->post('/auth/login', [
            'identifier' => $user->username,
            'password' => $password,
            'remember' => 'off',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'remember' => 'The remember checkbox must be checked or not.',
        ]);
    }

    public function test_login_fails_when_invalid_email_is_provided()
    {
        $response = $this->post('/auth/login', [
            'identifier' => 'test@test.com',
            'password' => 'invalid-password',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'identifier' => 'Email or username is incorrect.',
        ]);
        $this->assertGuest();
    }

    public function test_login_fails_when_invalid_username_is_provided()
    {
        $response = $this->post('/auth/login', [
            'identifier' => 'test',
            'password' => 'invalid-password',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'identifier' => 'Email or username is incorrect.',
        ]);
        $this->assertGuest();
    }

    public function test_login_fails_when_invalid_password_is_provided()
    {
        User::factory()->create([
            'email' => 'test@test.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/auth/login', [
            'identifier' => 'test@test.com',
            'password' => 'invalid-password',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'password' => 'Password is incorrect. Try again or click "Forgot Password" to reset your password.',
        ]);
        $this->assertGuest();
    }

    public function test_identifier_field_is_required()
    {
        $response = $this->post('/auth/login', [
            'password' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'identifier' => 'Email or username is required.',
        ]);
        $this->assertGuest();
    }

    public function test_password_field_is_required()
    {
        User::factory()->create(['username' => 'test']);

        $response = $this->post('/auth/login', [
            'identifier' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'password' => 'Password is required.',
        ]);

        $this->assertGuest();
    }

    public function test_identifier_must_not_be_greater_than_255_characters()
    {
        $response = $this->post('/auth/login', [
            'identifier' => Str::random(256),
            'password' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'identifier' => 'Email or username is incorrect.',
        ]);
        $this->assertGuest();
    }

    public function test_password_must_not_be_greater_than_60_characters()
    {
        $response = $this->post('/auth/login', [
            'identifier' => 'test',
            'password' => Str::random(61),
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'password' => 'Password is incorrect. Try again or click "Forgot Password" to reset your password.',
        ]);
        $this->assertGuest();
    }

    public function test_identifier_must_be_a_string()
    {
        $response = $this->post('/auth/login', [
            'identifier' => 123,
            'password' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'identifier' => 'Email or username is incorrect.',
        ]);
        $this->assertGuest();
    }

    public function test_password_must_be_a_string()
    {
        $response = $this->post('/auth/login', [
            'identifier' => 'test',
            'password' => 123,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'password' => 'Password is incorrect. Try again or click "Forgot Password" to reset your password.',
        ]);
        $this->assertGuest();
    }
}
