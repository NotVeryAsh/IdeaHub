<?php

namespace App\Services;

use App\Models\DefaultProfilePicture;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfilePictureService
{
    public static function getProfilePictureInitials(): ?string
    {
        // If user is logged in, return their initials or first two characters of their username
        if ($user = auth()->user()) {

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

    public static function update(User $user, $profilePicture)
    {
        // get user's current profile picture and the new profile picture
        $oldProfilePicture = $user->profile_picture;
        $newProfilePicture = $profilePicture;

        // store new profile picture and get its path
        $newProfilePicture = $newProfilePicture->store('', ['disk' => 'profile_pictures']);

        // If upload fails for whatever reason - possibly due to permissions
        if (! $newProfilePicture) {
            return redirect()->route('profile')->with(['status' => 'Profile picture could not be updated.']);
        }

        $user->update([
            'profile_picture' => $newProfilePicture,
        ]);

        if ($oldProfilePicture) {
            Storage::disk('public')->delete($oldProfilePicture);
        }
    }

    public static function remove($user): void
    {
        $oldProfilePicture = $user->profile_picture;

        $user->update([
            'profile_picture' => null,
        ]);

        // Do not delete default profile pictures
        if (self::checkIsDefault($oldProfilePicture)) {
            return;
        }

        Storage::disk('public')->delete($oldProfilePicture);
    }

    public static function checkIsDefault($profilePicture): bool
    {
        $defaultProfilePicture = DefaultProfilePicture::query()->where('path', $profilePicture)->first();
        $defaultProfilePicturePath = Storage::disk('default_profile_pictures')->path('');
        $containsDefaultProfilePicturePath = Str::contains($profilePicture, $defaultProfilePicturePath);

        if ($defaultProfilePicture !== null || $containsDefaultProfilePicturePath) {
            return false;
        }

        return true;
    }
}
