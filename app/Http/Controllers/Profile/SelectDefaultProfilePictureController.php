<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfilePictureRequest;
use App\Models\DefaultProfilePicture;
use App\Services\ProfilePictureService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SelectDefaultProfilePictureController extends Controller
{
    public function __invoke(Request $request, DefaultProfilePicture $defaultProfilePicture): RedirectResponse
    {
        $user = $request->user();

        $user->update([
            'profile_picture' => $defaultProfilePicture->path,
        ]);

        return redirect()->back()->with(['status' => 'Profile picture updated!']);
    }
}
