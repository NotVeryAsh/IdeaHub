<?php

namespace Profile;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UpdateProfilePictureTest extends TestCase
{
    public function test_profile_picture_is_saved_when_updating_profile_picture()
    {
        $user = User::factory()->create();

        // Log in as user
        $this->actingAs($user);

        Storage::fake('public');

        $response = $this->patch('/profile/profile-picture', [
            'profile_picture' => UploadedFile::fake()->image('profile_picture.jpg', 100, 100)->size(100),
        ]);

        $response->assertSessionHas([
            'status' => 'Profile updated!',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'profile_picture' => "images/users/profile_pictures/$user->profile_picture",
        ]);

        Storage::disk('public')->assertExists("images/users/profile_pictures/$user->profile_picture");
    }

    public function test_profile_picture_is_required_when_updating_profile_picture()
    {
        $user = User::factory()->create();

        // Log in as user
        $this->actingAs($user);

        Storage::fake('public');

        $response = $this->patch('/profile/profile-picture', [
            'profile_picture' => '',
        ]);

        $response->assertSessionHasErrors([
            'profile_picture' => 'Profile picture is required.',
        ]);
    }

    public function test_profile_picture_must_be_5MB_or_less()
    {
        $user = User::factory()->create();

        // Log in as user
        $this->actingAs($user);

        Storage::fake('public');

        $response = $this->patch('/profile/profile-picture', [
            'profile_picture' => UploadedFile::fake()->image('profile_picture.jpg')->size(6000),
        ]);

        $response->assertSessionHasErrors([
            'profile_picture' => 'Profile picture must be 5MB or less.',
        ]);
    }

    public function test_profile_picture_must_be_image()
    {
        $user = User::factory()->create();

        // Log in as user
        $this->actingAs($user);

        Storage::fake('public');

        $response = $this->patch('/profile/profile-picture', [
            'profile_picture' => UploadedFile::fake()->create('profile_picture.pdf'),
        ]);

        $response->assertSessionHasErrors([
            'profile_picture' => 'Profile picture must be an image.',
        ]);
    }

    public function test_profile_picture_must_be_jpeg_jpg_png_webp_or_gif()
    {
        $user = User::factory()->create();

        // Log in as user
        $this->actingAs($user);

        Storage::fake('public');

        $response = $this->patch('/profile/profile-picture', [
            'profile_picture' => UploadedFile::fake()->image('profile_picture.bmp'),
        ]);

        $response->assertSessionHasErrors([
            'profile_picture' => 'Profile picture must be a JPEG, JPG, PNG, WEBP or GIF.',
        ]);
    }

    public function test_profile_picture_resolution_must_be_800x800_or_less()
    {
        $user = User::factory()->create();

        // Log in as user
        $this->actingAs($user);

        Storage::fake('public');

        $response = $this->patch('/profile/profile-picture', [
            'profile_picture' => UploadedFile::fake()->image('profile_picture.jpg', 801, 801),
        ]);

        $response->assertSessionHasErrors([
            'profile_picture' => 'Profile picture must be 800x800 or less.',
        ]);
    }

    public function test_old_uploaded_profile_picture_is_deleted_when_new_one_is_uploaded()
    {
        $user = User::factory()->create([
            'profile_picture' => 'profile_picture.jpg',
        ]);

        // Log in as user
        $this->actingAs($user);

        Storage::fake('public');

        UploadedFile::fake()->image('profile_picture.jpg', 100, 100)->size(100)->store('images/users/profile_pictures');

        $response = $this->patch('/profile/profile-picture', [
            'profile_picture' => UploadedFile::fake()->image('new_profile_picture.jpg', 100, 100)->size(100),
        ]);

        $response->assertSessionHas([
            'status' => 'Profile updated!',
        ]);

        Storage::disk('public')->assertMissing('images/users/profile_pictures/profile_picture.jpg');
    }

    public function test_profile_picture_is_updated()
    {
        $user = User::factory()->create([
            'profile_picture' => 'profile_picture.jpg',
        ]);

        // Log in as user
        $this->actingAs($user);

        Storage::fake('public');

        UploadedFile::fake()->image('profile_picture.jpg', 100, 100)->size(100)->store('images/users/profile_pictures');

        $response = $this->patch('/profile/profile-picture', [
            'profile_picture' => UploadedFile::fake()->image('new_profile_picture.jpg', 100, 100)->size(100),
        ]);

        $response->assertSessionHas([
            'status' => 'Profile updated!',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'profile_picture' => "images/users/profile_pictures/$user->profile_picture",
        ]);

        Storage::disk('public')->assertExists("images/users/profile_pictures/$user->profile_picture");
    }

    public function test_cannot_update_profile_picture_if_not_authenticated()
    {
        $response = $this->patch('/profile/profile-picture', [
            'profile_picture' => UploadedFile::fake()->image('profile_picture.jpg', 100, 100)->size(100),
        ]);

        $response->assertRedirect('/login');
    }
}
