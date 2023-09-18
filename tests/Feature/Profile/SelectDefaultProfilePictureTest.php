<?php

namespace Profile;

use App\Models\DefaultProfilePicture;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SelectDefaultProfilePictureTest extends TestCase
{
    public function test_can_select_default_profile_picture()
    {
        Storage::fake();

        $user = User::factory()->create([
            'profile_picture' => 'profile_picture.jpg',
        ]);

        // Log in as user
        $this->actingAs($user);

        // update profile picture to default profile picture
        $defaultProfilePicture = DefaultProfilePicture::factory()->create();
        $response = $this->patch("/profile-picture/default/$defaultProfilePicture->id");

        // Assert message was returned
        $response->assertSessionHas([
            'status' => 'Profile picture updated!',
        ]);

        // Assert users table is updated with new profile picture path
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'profile_picture' => $defaultProfilePicture->path,
        ]);
    }

    public function test_404_is_removed_when_invalid_profile_picture_is_provided()
    {
        $user = User::factory()->create([
            'profile_picture' => 'profile_picture.jpg',
        ]);

        // Log in as user
        $this->actingAs($user);

        // try to update profile picture with a default profile picture that doesn't exist
        $response = $this->patch('/profile-picture/default/test');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'profile_picture' => 'profile_picture.jpg',
        ]);

        $response->assertStatus(404);
    }

    public function test_old_none_default_profile_picture_is_removed_when_selecting_default_profile_picture()
    {
        Storage::fake();

        // Give user a default uploaded profile picture
        $user = User::factory()->create([
            'profile_picture' => 'profile_picture.jpg',
        ]);

        // Log in as user
        $this->actingAs($user);

        // Store fake profile picture
        UploadedFile::fake()->image('profile_picture.jpg', 100, 100)->size(100)->store('images/users/profile_pictures');

        // Update profile picture with a default profile picture
        $defaultProfilePicture = DefaultProfilePicture::factory()->create();
        $response = $this->patch("/profile-picture/default/$defaultProfilePicture->id");

        // Assert message was returned
        $response->assertSessionHas([
            'status' => 'Profile picture updated!',
        ]);

        // Assert user's profile picture is now the default profile picture
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'profile_picture' => $defaultProfilePicture->path,
        ]);

        // assert old profile picture was deleted from storage
        Storage::assertMissing('images/users/profile_pictures/profile_picture.jpg');
    }

    public function test_default_profile_picture_is_not_deleted_when_selecting_new_default_profile_picture()
    {
        Storage::fake();

        // Create default profile pictures
        $oldDefaultProfilePicture = DefaultProfilePicture::factory()->create();

        $newDefaultProfilePicture = DefaultProfilePicture::factory()->create();

        // Give user a default uploaded profile picture
        $user = User::factory()->create([
            'profile_picture' => $oldDefaultProfilePicture->path,
        ]);

        // Log in as user
        $this->actingAs($user);

        // Update profile picture with new default profile picture
        $response = $this->patch("/profile-picture/default/$newDefaultProfilePicture->id");

        // Assert message was returned
        $response->assertSessionHas([
            'status' => 'Profile picture updated!',
        ]);

        // Assert user's profile picture is now the new default profile picture
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'profile_picture' => $newDefaultProfilePicture->path,
        ]);

        // assert default profile pictures were not deleted
        Storage::assertExists($oldDefaultProfilePicture->path);
    }

    public function test_can_not_select_default_profile_picture_if_not_authenticated()
    {
        Storage::fake();

        $defaultProfilePicture = DefaultProfilePicture::factory()->create();

        // try to update profile picture with a default profile picture
        $response = $this->patch("/profile-picture/default/$defaultProfilePicture->id");

        // Assert redirected to login page
        $response->assertRedirect('/login');
    }
}
