<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\ResetPasswordService;
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
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Reset user password
     */
    public function reset(ResetPasswordRequest $request): RedirectResponse
    {
        $status = ResetPasswordService::reset($request);

        $statusMessage = __($status);

        // Reset password was not successful
        if ($status !== Password::PASSWORD_RESET) {
            return back()->withErrors(['email' => $statusMessage]);
        }

        // Reset password was successful
        return redirect()->route('login')->with(['status' => $statusMessage]);
    }
}
