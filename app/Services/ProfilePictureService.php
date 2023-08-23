<?php

namespace App\Services;

use App\Models\User;

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
}
