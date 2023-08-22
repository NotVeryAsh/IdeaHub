<?php

namespace App\Services;

use App\Models\User;

class ProfilePictureService
{
    public static function getProfilePictureInitials(): ?string
    {
        // If user is logged in, return their initials or first two characters of their username
        if ($user = auth()->user()) {
            $firstName = $user->first_name;
            $lastName = $user->last_name;

            if ($firstName && $lastName) {
                return $firstName[0].$lastName[0];
            } else {
                return $user->username[0].$user->username[1];
            }
        }

        return null;
    }
}
