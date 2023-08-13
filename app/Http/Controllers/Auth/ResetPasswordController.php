<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    /**
     * Show reset password page
     */
    public function index($token): View
    {
        return view('auth.forgot-password', ['token' => $token]);
    }

    public function sendResetLinkNotification(ForgotPasswordRequest $request): RedirectResponse
    {
        // Send password reset link to user
        $status = Password::sendResetLink(['email' => $request->validated('email')]);

        $statusMessage = __($status);

        // Sending password reset notification was not successful
        if ($status !== Password::RESET_LINK_SENT) {
            return back()->withErrors(['email' => $statusMessage]);
        }

        // Sending password reset notification was successful
        return back()->with(['status' => $statusMessage]);
    }
}
