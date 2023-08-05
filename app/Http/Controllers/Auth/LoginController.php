<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // TODO Turn this function into a stateless-friendly endpoint ie. meaningful status codes and json data

    public function index(): Response
    {
        return response()->view('auth.login');
    }

    public function authenticate(LoginRequest $request): Response|RedirectResponse
    {
        $identifier = $request->validated('identifier');
        $password = $request->validated('password');

        // Check if identifier is an email or a username
        $fieldType = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Attempt to authenticate the user with the identifier (username or email) and password
        $authIsSuccessful = Auth::attempt([$fieldType => $identifier, 'password' => $password]);

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
