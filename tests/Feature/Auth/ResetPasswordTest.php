<?php

namespace Auth;

use Illuminate\Support\Str;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    public function test_can_reset_password()
    {

    }

    public function test_logged_in_user_cannot_send_forgot_password_email()
    {

    }

    public function test_404_returned_when_token_is_invalid()
    {

    }

    public function test_password_is_required_when_resetting_password()
    {
        $response = $this->post('/auth/reset-password', [
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
        $response = $this->post('/auth/reset-password', [
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
        $response = $this->post('/auth/reset-password', [
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
        $response = $this->post('/auth/reset-password', [
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
        $response = $this->post('/auth/reset-password');

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'email' => 'Email is required.',
        ]);
    }

    public function test_email_field_must_be_a_valid_email_when_sending_forgot_password_email()
    {
        $response = $this->post('/auth/reset-password', [
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
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'email' => 'Email not found.',
        ]);
    }

    public function test_token_is_required_when_resetting_password()
    {
        $response = $this->post('/auth/reset-password', [
            'username' => 'username',
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'email' => 'Token is required.',
        ]);
    }
}
