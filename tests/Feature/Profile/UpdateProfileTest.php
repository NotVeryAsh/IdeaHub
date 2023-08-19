<?php

namespace Profile;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class UpdateProfileTest extends TestCase
{
    public function test_can_update_profile()
    {
        // Make user with initial credentials
        $user = User::factory()->create([
            'email' => 'test@test.com',
            'password' => Hash::make('test'),
            'username' => 'test',
            'first_name' => 'test',
            'last_name' => 'test',
        ]);

        // Log in as user
        $this->actingAs($user);

        // Update user with new credentials
        $response = $this->patch('/profile', [
            'email' => 'example@example.com',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
            'username' => 'new_username',
            'first_name' => 'new_first_name',
            'last_name' => 'new_last_name',
        ]);

        // Check that user is redirected to profile page
        $response->assertRedirectToRoute('profile');
        $response->assertSessionHas(['status' => 'Profile updated!']);

        // Check that user is updated in database
        $this->assertDatabaseHas('users', [
            'email' => 'example@example.com',
            'username' => 'new_username',
            'first_name' => 'new_first_name',
            'last_name' => 'new_last_name',
        ]);

        // Check that user's old details are not in database
        $this->assertDatabaseMissing('users', [
            'email' => 'test@test.com',
            'username' => 'test',
            'first_name' => 'test',
            'last_name' => 'test',
        ]);
    }

    public function test_username_is_required_when_present_when_updating_profile()
    {
        $user = User::factory()->create();

        // Log in as user
        $this->actingAs($user);

        $response = $this->patch('/profile', [
            'username' => '',
        ]);

        $response->assertSessionHasErrors([
            'username' => 'Username is required.',
        ]);
    }

    public function test_username_must_be_unique_when_updating_profile()
    {
        // Create two users with the same username
        User::factory()->create([
            'username' => 'username',
        ]);

        $user = User::factory()->create();

        // Log in as user
        $this->actingAs($user);

        $response = $this->patch('/profile', [
            'username' => 'username',
        ]);

        $response->assertSessionHasErrors([
            'username' => 'Username has already been taken.',
        ]);
    }

    public function test_own_username_is_ignored_when_updating_profile()
    {
        $user = User::factory()->create([
            'username' => 'username',
        ]);

        // Log in as user
        $this->actingAs($user);

        $response = $this->patch('/profile', [
            'username' => 'username',
        ]);

        $response->assertSessionHas([
            'status' => 'Profile updated!',
        ]);
    }

    public function test_username_must_be_greater_than_3_characters_when_updating_profile()
    {
        $user = User::factory()->create();

        // Log in as user
        $this->actingAs($user);

        $response = $this->patch('/profile', [
            'username' => 'a',
        ]);

        $response->assertSessionHasErrors([
            'username' => 'Username must be at least 3 characters.',
        ]);
    }

    public function test_username_must_be_not_be_greater_than_20_characters_when_updating_profile()
    {
        $user = User::factory()->create();

        // Log in as user
        $this->actingAs($user);

        $response = $this->patch('/profile', [
            'username' => Str::random(21),
        ]);

        $response->assertSessionHasErrors([
            'username' => 'Username must not be greater than 20 characters.',
        ]);
    }

    public function test_username_must_comply_with_alpha_dash_rule_when_updating_profile()
    {
        $user = User::factory()->create();

        // Log in as user
        $this->actingAs($user);

        $response = $this->patch('/profile', [
            'username' => 'username!',
        ]);

        $response->assertSessionHasErrors([
            'username' => 'Username must only contain letters, numbers, dashes, and underscores.',
        ]);
    }

    public function test_can_update_username_once_every_six_hours()
    {
        // Create user that has updated their username now
        $user = User::factory()->create(['username_updated_at' => now()]);

        // Log in as user
        $this->actingAs($user);

        $response = $this->patch('/profile', [
            'username' => 'username',
        ]);

        $response->assertSessionHasErrors([
            'username' => 'You may update your username again in 5 hours.',
        ]);
    }

    public function test_password_is_required_when_present_when_updating_profile()
    {
        $user = User::factory()->create();

        // Log in as user
        $this->actingAs($user);

        $response = $this->patch('/profile', [
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'Password is required.',
        ]);
    }

    public function test_password_must_be_greater_than_8_characters_when_updating_profile()
    {
        $user = User::factory()->create();

        // Log in as user
        $this->actingAs($user);

        $response = $this->patch('/profile', [
            'password' => 'a',
            'password_confirmation' => 'a',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'Password must be at least 8 characters.',
        ]);
    }

    public function test_password_must_be_not_be_greater_than_60_characters_when_updating_profile()
    {
        $user = User::factory()->create();

        // Log in as user
        $this->actingAs($user);

        $invalidPassword = Str::random(61);

        $response = $this->patch('/profile', [
            'password' => $invalidPassword,
            'password_confirmation' => $invalidPassword,
        ]);

        $response->assertSessionHasErrors([
            'password' => 'Password must not be greater than 60 characters.',
        ]);
    }

    public function test_password_must_be_confirmed_when_updating_profile()
    {
        $user = User::factory()->create();

        // Log in as user
        $this->actingAs($user);

        $response = $this->patch('/profile', [
            'password' => 'TestPassword',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'Passwords do not match.',
        ]);
    }

    public function test_email_is_required_when_present__updating_profile()
    {
        $user = User::factory()->create();

        // Log in as user
        $this->actingAs($user);

        $response = $this->patch('/profile', [
            'email' => '',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Email is required.',
        ]);
    }

    public function test_email_must_be_valid_when_updating_profile()
    {
        $user = User::factory()->create();

        // Log in as user
        $this->actingAs($user);

        $response = $this->patch('/profile', [
            'email' => 'email',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Email must be a valid email address.',
        ]);
    }

    public function test_email_must_be_unique_when_updating_profile()
    {
        User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $user = User::factory()->create();

        // Log in as user
        $this->actingAs($user);

        $response = $this->patch('/profile', [
            'email' => 'test@test.com',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Email has already been taken.',
        ]);
    }

    public function test_email_must_be_not_be_greater_than_255_characters_when_updating_profile()
    {
        $user = User::factory()->create();

        // Log in as user
        $this->actingAs($user);

        $response = $this->patch('/profile', [
            'email' => Str::random(256).'@example.com',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Email must not be greater than 255 characters.',
        ]);
    }

    public function test_own_email_is_ignored_when_updating_profile()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        // Log in as user
        $this->actingAs($user);

        $response = $this->patch('/profile', [
            'email' => $user->email,
        ]);

        $response->assertSessionHas([
            'status' => 'Profile updated!',
        ]);
    }

    public function test_first_name_must_be_string_when_updating_profile()
    {
        $user = User::factory()->create();

        // Log in as user
        $this->actingAs($user);

        $response = $this->patch('/profile', [
            'first_name' => 1,
        ]);

        $response->assertSessionHasErrors([
            'first_name' => 'First name is invalid.',
        ]);
    }

    public function test_first_name_must_not_be_greater_than_35_characters_when_updating_profile()
    {
        $user = User::factory()->create();

        // Log in as user
        $this->actingAs($user);

        $response = $this->patch('/profile', [
            'first_name' => Str::random(36),
        ]);

        $response->assertSessionHasErrors([
            'first_name' => 'First name must not be greater than 35 characters.',
        ]);
    }

    public function test_last_name_must_be_string_when_updating_profile()
    {
        $user = User::factory()->create();

        // Log in as user
        $this->actingAs($user);

        $response = $this->patch('/profile', [
            'last_name' => 1,
        ]);

        $response->assertSessionHasErrors([
            'last_name' => 'Last name is invalid.',
        ]);
    }

    public function test_last_name_must_not_be_greater_than_35_characters_when_updating_profile()
    {
        $user = User::factory()->create();

        // Log in as user
        $this->actingAs($user);

        $response = $this->patch('/profile', [
            'last_name' => Str::random(36),
        ]);

        $response->assertSessionHasErrors([
            'last_name' => 'Last name must not be greater than 35 characters.',
        ]);
    }

    public function test_authentication_is_required_to_update_profile()
    {
        $response = $this->patch('/profile');

        $response->assertRedirect('/login');
    }
}
