<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_email()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
        ]);

        $response = $this->postJson('/auth/login', [
            'username' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_login_with_valid_username()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
        ]);

        $response = $this->postJson('/auth/login', [
            'username' => $user->username,
            'password' => $password,
        ]);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_when_invalid_email_is_provided()
    {
        $response = $this->postJson('/auth/login', [
            'email' => 'test@test.com',
            'password' => 'invalid-password',
        ]);

        $response->assertStatus(401);
        $response->assertSessionHasErrors([
            'username' => 'Email or username is incorrect.',
        ]);
        $this->assertGuest();
    }

    public function test_login_fails_when_invalid_username_is_provided()
    {
        $response = $this->postJson('/auth/login', [
            'user' => 'test',
            'password' => 'invalid-password',
        ]);

        $response->assertStatus(401);
        $response->assertSessionHasErrors([
            'username' => 'Email or username is incorrect.',
        ]);
        $this->assertGuest();
    }

    public function test_login_fails_when_invalid_password_is_provided()
    {
        User::factory()->create([
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/auth/login', [
            'username' => 'test@test.com',
            'password' => 'invalid-password',
        ]);

        $response->assertStatus(401);
        $response->assertSessionHasErrors([
            'password' => 'Password is incorrect. Try again or click "Forgot Password" to reset your password.',
        ]);
        $this->assertGuest();
    }

    public function test_login_fails_when_recaptcha_is_invalid()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
        ]);

        $response = $this->postJson('/auth/login', [
            'username' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(401);
        $this->assertGuest();
    }
}
