<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfilePictureRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ProfilePictureController extends Controller
{
    public function update(UpdateProfilePictureRequest $request): RedirectResponse
    {
        $user = $request->user();

        $oldProfilePicture = $user->profile_picture;
        $newProfilePicture = $request->validated('profile_picture');

        // store new profile picture and get its path
        $newProfilePicture = $newProfilePicture->store('images/users/profile_pictures');

        if (! $newProfilePicture) {
            return redirect()->route('profile')->with(['status' => 'Profile picture could not be updated.']);
        }

        $user->update([
            'profile_picture' => $newProfilePicture,
        ]);

        if ($oldProfilePicture) {
            Storage::disk('public')->delete($oldProfilePicture);
        }

        return redirect()->route('profile')->with(['status' => 'Profile updated!']);
    }
}
