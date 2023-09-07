<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\DefaultProfilePicture;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SelectDefaultProfilePictureController extends Controller
{
    public function __invoke(Request $request, DefaultProfilePicture $picture = null): RedirectResponse
    {
        $user = $request->user();

        // set user's profile picture to the selected default profile picture or null if it doesn't exist
        $user->update([
            'profile_picture' => $picture?->path,
        ]);

        return redirect()->back()->with(['status' => 'Profile picture updated!']);
    }
}
