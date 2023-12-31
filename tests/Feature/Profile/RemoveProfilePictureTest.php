<?php

namespace Tests\Feature\Profile;

use App\Models\DefaultProfilePicture;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RemoveProfilePictureTest extends TestCase
{
    public function test_can_remove_profile_picture()
    {
        Storage::fake();

        $user = User::factory()->create([
            'profile_picture' => 'profile_picture.jpg',
        ]);

        // Log in as user
        $this->actingAs($user);

        // Store fake profile picture
        UploadedFile::fake()->image('profile_picture.jpg', 100, 100)->size(100)->store(config('filesystems.default_profile_pictures_path'));

        // Delete auth user's profile picture
        $response = $this->delete('/profile-picture');

        // Assert message was returned
        $response->assertSessionHas([
            'status' => 'Profile picture removed!',
        ]);

        // Assert user no longer has profile picture
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'profile_picture' => null,
        ]);
    }

    public function test_can_not_remove_profile_picture_if_not_authenticated()
    {
        // Attempt to delete profile picture
        $response = $this->delete('/profile-picture');

        $response->assertRedirect('/login');
    }

    public function test_profile_picture_is_removed_from_storage()
    {
        Storage::fake();

        $user = User::factory()->create([
            'profile_picture' => 'profile_picture.jpg',
        ]);

        // Log in as user
        $this->actingAs($user);

        // Store a fake profile picture
        UploadedFile::fake()->image('profile_picture.jpg', 100, 100)->size(100)->store(config('filesystems.profile_pictures_path'));

        // delete auth user's profile picture
        $this->delete('/profile-picture');

        // assert profile picture was deleted from storage
        Storage::assertMissing('images/users/profile_pictures/profile_picture.jpg');
    }

    public function test_default_profile_picture_is_not_deleted_when_removing_profile_picture()
    {
        Storage::fake();

        // Create default profile picture
        $defaultProfilePicture = DefaultProfilePicture::factory()->create();

        // Give user a default uploaded profile picture
        $user = User::factory()->create([
            'profile_picture' => $defaultProfilePicture->path,
        ]);

        // Log in as user
        $this->actingAs($user);

        // Remove user's current profile picture
        $this->delete('/profile-picture');

        // Assert user's profile picture has been removed
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'profile_picture' => null,
        ]);

        // assert default profile picture was not deleted
        Storage::assertExists($defaultProfilePicture->path);
    }
}
