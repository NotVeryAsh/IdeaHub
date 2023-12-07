<?php

namespace App\Services;

use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordService
{
    public static function reset(ResetPasswordRequest $request): string
    {
        return Password::reset(
            $request->only(['email', 'password', 'token']),
            function (User $user, string $password) use ($request) {

                // Either update user's remember token to remember them, or set it to null
                $rememberToken = $request->validated('remember') ? Str::random(60) : null;

                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => $rememberToken,
                ])->save();

                event(new PasswordReset($user));
            }
        );
    }
}
