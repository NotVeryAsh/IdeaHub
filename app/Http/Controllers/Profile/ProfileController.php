<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
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
        return view('profile.edit', ['user' => auth()->user()]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();

        $fields = $request->validated();

        // Check if array contains password key, and if password value is null, remove it
        if (array_key_exists('password', $fields) && ! $fields['password']) {
            unset($fields['password']);
        }

        // update username_updated_at field
        if (isset($fields['username']) && $fields['username'] !== $user->username) {
            $fields['username_updated_at'] = now();
        }

        $user->update(
            $fields
        );

        return redirect()->route('profile')->with(['status' => 'Profile updated!']);
    }
}