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

        // Update profile picture
        $response = $this->patch('/profile/profile-picture', [
            'profile_picture' => UploadedFile::fake()->image('profile_picture.jpg', 100, 100)->size(100),
        ]);

        // Assert message was returned
        $response->assertSessionHas([
            'status' => 'Profile picture updated!',
        ]);

        // Assert profile picture is updated in database
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'profile_picture' => $user->profile_picture,
        ]);

        // Assert profile picture is saved in storage
        Storage::assertExists($user->profile_picture);
    }

    public function test_profile_picture_is_required_when_updating_profile_picture()
    {
        $user = User::factory()->create();

        // Log in as user
        $this->actingAs($user);

        // Attempt to update profile picture without providing a profile picture
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

        // Attempt to upload profile picture that is 6MB
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

        // Attempt to upload profile picture that is not an image
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

        // Attempt to upload profile picture with extension of bmp
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

        // Attempt to upload profile picture with resolution of 801x801
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

        // Store fake profile picture
        Storage::fake('public');
        UploadedFile::fake()->image('profile_picture.jpg', 100, 100)->size(100)->store('images/users/profile_pictures');

        // Update new profile picture
        $response = $this->patch('/profile/profile-picture', [
            'profile_picture' => UploadedFile::fake()->image('new_profile_picture.jpg', 100, 100)->size(100),
        ]);

        // Assert message returned
        $response->assertSessionHas([
            'status' => 'Profile picture updated!',
        ]);

        // Assert old profile picture is deleted
        Storage::disk('public')->assertMissing('images/users/profile_pictures/profile_picture.jpg');
    }

    public function test_profile_picture_is_updated()
    {
        $user = User::factory()->create([
            'profile_picture' => 'profile_picture.jpg',
        ]);

        // Log in as user
        $this->actingAs($user);

        // Store fake profile picture
        Storage::fake('public');
        UploadedFile::fake()->image('profile_picture.jpg', 100, 100)->size(100)->store('images/users/profile_pictures');

        $response = $this->patch('/profile/profile-picture', [
            'profile_picture' => UploadedFile::fake()->image('new_profile_picture.jpg', 100, 100)->size(100),
        ]);

        // Assert message returned
        $response->assertSessionHas([
            'status' => 'Profile picture updated!',
        ]);

        // Assert new profile picture is saved in the Database
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'profile_picture' => $user->profile_picture,
        ]);

        // Assert profile picture is saved in storage
        Storage::assertExists($user->profile_picture);
    }

    public function test_cannot_update_profile_picture_if_not_authenticated()
    {
        // Attempt to store fake profile picture while unauthenticated
        $response = $this->patch('/profile/profile-picture', [
            'profile_picture' => UploadedFile::fake()->image('profile_picture.jpg', 100, 100)->size(100),
        ]);

        $response->assertRedirect('/login');
    }
}
