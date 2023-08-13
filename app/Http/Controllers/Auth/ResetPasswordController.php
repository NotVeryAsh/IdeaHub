<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    /**
     * Show reset password page
     */
    public function index($token): View
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Reset user password
     */
    public function reset(ResetPasswordRequest $request): RedirectResponse
    {
        $status = Password::reset(
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

        $statusMessage = __($status);

        // Sending password reset notification was not successful
        if ($status !== Password::PASSWORD_RESET) {
            return back()->withErrors(['email' => $statusMessage]);
        }

        // Sending password reset notification was successful
        return redirect()->route('login')->with(['status' => $statusMessage]);
    }
}
