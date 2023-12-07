<?php

namespace App\Services;

use App\Models\DefaultProfilePicture;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfilePictureService
{
    // Get user's initials to display as profile picture if they don't have one
    public static function getProfilePictureInitials(User $user = null): ?string
    {
        $user = $user ?? auth()->user();

        // If user is logged in, return their initials or first two characters of their username
        if ($user) {

            // Get first and last name
            $firstName = $user->first_name;
            $lastName = $user->last_name;

            // If user has first and last name, return first letter of each
            if ($firstName && $lastName) {
                return $firstName[0].$lastName[0];

                // If user has username only, return first two characters of username
            } else {
                return $user->username[0].$user->username[1];
            }
        }

        // Return empty profile picture
        return null;
    }

    // Update user's profile picture and delete old profile picture if it isn't a default profile picture
    public static function update(User $user, $newProfilePicture)
    {
        // get user's current profile picture and the new submitted profile picture
        $oldProfilePicture = $user->profile_picture;

        // store new profile picture and get its path
        $newProfilePicture = $newProfilePicture->store(config('filesystems.profile_pictures_path'));

        // If upload fails for whatever reason - possibly due to permissions
        if (! $newProfilePicture) {
            return redirect()->route('profile')->with(['status' => 'Profile picture could not be updated.']);
        }

        $user->update([
            'profile_picture' => $newProfilePicture,
        ]);

        // Only delete old profile picture if it exists and is not a default profile picture
        if ($oldProfilePicture && ! self::checkIsDefault($oldProfilePicture)) {
            Storage::delete($oldProfilePicture);
        }
    }

    // Remove user's profile picture and delete old profile picture if it isn't a default profile picture
    public static function remove($user): void
    {
        $oldProfilePicture = $user->profile_picture;

        // Remove user's current profile picture
        $user->update([
            'profile_picture' => null,
        ]);

        // Do not delete default profile pictures
        if (self::checkIsDefault($oldProfilePicture)) {
            return;
        }

        // Delete old profile picture
        Storage::delete($oldProfilePicture);
    }

    // Check if profile picture is a default profile picture
    public static function checkIsDefault($profilePicture): bool
    {
        // Get default profile picture from database with path
        $defaultProfilePicture = DefaultProfilePicture::query()->where('path', $profilePicture)->first();

        // Get the directory path to where default profile pictures are stored
        $defaultProfilePicturePath = config('filesystems.default_profile_pictures_path');

        // Check if the default profile picture exists or if the profile picture path is in the default profile picture directory
        $containsDefaultProfilePicturePath = Str::contains($profilePicture, $defaultProfilePicturePath);

        // Not a default profile picture
        if (! $defaultProfilePicture || ! $containsDefaultProfilePicturePath) {
            return false;
        }

        return true;
    }

    // Update user's profile picture to selected default profile picture and delete current profile picture if it isn't a default profile picture
    public static function selectDefault(User $user, $newProfilePicture): void
    {
        $oldProfilePicture = $user->profile_picture;

        // set user's profile picture to the selected default profile picture or null if it doesn't exist
        $user->update([
            'profile_picture' => $newProfilePicture->path,
        ]);

        // Only delete old profile picture if it exists and is not a default profile picture
        if ($oldProfilePicture && ! ProfilePictureService::checkIsDefault($oldProfilePicture)) {
            Storage::delete($oldProfilePicture);
        }
    }
}
