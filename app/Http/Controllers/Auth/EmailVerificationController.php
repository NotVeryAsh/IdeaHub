<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    public function showNotice(): View
    {
        return view('auth.verify-email-notice');
    }

    public function resend(): RedirectResponse
    {
        $user = request()->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard')->with('message', 'Email already verified!');
        }

        request()->user()->sendEmailVerificationNotification();

        // Make sure we always take the user back to the verification notice page
        return redirect()->route('verification.notice')->with('message', 'Verification link sent!');
    }

    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard')->with('message', 'Email already verified!');
        }

        return redirect()->route('dashboard');
    }
}
