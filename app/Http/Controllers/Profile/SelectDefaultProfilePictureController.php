<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\DefaultProfilePicture;
use App\Services\ProfilePictureService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SelectDefaultProfilePictureController extends Controller
{
    public function __invoke(Request $request, DefaultProfilePicture $picture): RedirectResponse
    {
        $user = $request->user();

        // Select default profile picture
        ProfilePictureService::selectDefault($user, $picture);

        return redirect()->back()->with(['status' => 'Profile picture updated!']);
    }
}
