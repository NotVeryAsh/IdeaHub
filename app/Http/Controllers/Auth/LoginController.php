<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\TeamInvitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    // TODO Turn these functions into stateless-friendly endpoints ie. meaningful status codes and json data

    /**
     * Return the login view
     */
    public function index(Request $request): RedirectResponse|Response
    {
        $invitation = TeamInvitation::query()->where('token', $request->get('token'))->first();

        return response()->view('auth.login', [
            'invitation' => $invitation,
        ]);
    }

    /**
     * Authenticate the user with username or email - and password
     */
    public function authenticate(LoginRequest $request): RedirectResponse
    {
        $identifier = $request->validated('identifier');
        $password = $request->validated('password');
        $shouldRemember = $request->validated('remember');

        // Check if identifier is an email or a username
        $fieldType = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Attempt to authenticate the user with the identifier (username or email) and password
        $authIsSuccessful = Auth::attempt([$fieldType => $identifier, 'password' => $password], $shouldRemember);

        // Password is incorrect - we have already established that a user with the identifier exists
        if (! $authIsSuccessful) {

            // Return back with password error message
            return redirect()->back()->withErrors([
                'password' => 'Password is incorrect. Try again or click "Forgot Password" to reset your password.',
            ]);
        }

        // Regenerate the session to prevent session fixation attacks
        $request->session()->regenerate();

        // Redirect the user to the intended page, or the dashboard if there is no intended page.
        return redirect()->intended('dashboard');
    }
}
