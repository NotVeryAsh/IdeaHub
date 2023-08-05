<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    /**
     * Show email verification notice page
     */
    public function showNotice(): View
    {
        return view('auth.verify-email-notice');
    }

    /**
     * Resend email verification email
     */
    public function resend(): RedirectResponse
    {
        $user = request()->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard')->with('message', 'Email already verified!');
        }

        $user->sendEmailVerificationNotification();

        // Make sure we always take the user back to the verification notice page
        return redirect()->route('verification.notice')->with('message', 'Verification link sent!');
    }

    /**
     * Verify email address
     */
    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();
        $redirect = redirect()->route('dashboard');

        // redirect to dashboard with Email Verified message if the user is already verified
        return $request->user()->hasVerifiedEmail() ? $redirect->with('message', 'Email already verified!') : $redirect;
    }
}
