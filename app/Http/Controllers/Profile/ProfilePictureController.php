<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfilePictureRequest;
use App\Services\ProfilePictureService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfilePictureController extends Controller
{
    public function update(UpdateProfilePictureRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Update user's current profile picture
        ProfilePictureService::update($user, $request->profile_picture);

        return redirect()->back()->with(['status' => 'Profile picture updated!']);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        ProfilePictureService::remove($user);

        return redirect()->back()->with(['status' => 'Profile picture removed!']);
    }
}
