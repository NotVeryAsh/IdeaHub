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

                // Update user's password
                $user->update([
                    'password' => Hash::make($password),
                ]);

                // Remember user if they ticked the remember me checkbox
                if ($request->validated('remember')) {
                    $user->setRememberToken(Str::random(60));
                }

                event(new PasswordReset($user));
            }
        );
    }
}
