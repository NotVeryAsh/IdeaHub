<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Models\DefaultProfilePicture;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(User $user = null): View
    {
        $user = $user ?? auth()->user();

        return view('profile.index', ['user' => $user, 'viewing_self' => auth()->user()->is($user)]);
    }

    public function edit(): View
    {
        $user = request()->user();

        return view('profile.edit', [
            'user' => $user,
            'profilePicture' => $user->profile_picture,
            'defaultProfilePictures' => DefaultProfilePicture::all()
        ]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();

        $fields = $request->validated();

        // Only update password if it's not empty
        if (array_key_exists('password', $fields) && ! $fields['password']) {
            unset($fields['password']);
        }

        // Only update username if it's not empty
        if (isset($fields['username']) && $fields['username'] !== $user->username) {
            $fields['username_updated_at'] = now();
        }

        $user->update(
            $fields
        );

        return redirect()->route('profile')->with(['status' => 'Profile updated!']);
    }
}
