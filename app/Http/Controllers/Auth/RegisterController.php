<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\RegisteredUser;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    // TODO Turn these functions into stateless-friendly endpoints ie. meaningful status codes and json data

    /**
     * Return the register view
     */
    public function index(): Response
    {
        return response()->view('auth.register');
    }

    /**
     * Register and authenticate the user
     */
    public function authenticate(RegisterRequest $request): RedirectResponse
    {
        // Get validated data
        $username = $request->validated('username');
        $email = $request->validated('email');
        $password = $request->validated('password');
        $remember = $request->validated('remember');

        // Create a user based on validated data
        $user = User::query()->create([
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        // Authenticate user
        Auth::login($user, $remember);

        // Send welcome email
        Mail::to($user)->queue(new RegisteredUser($user));

        // Send Verify email notifications
        event(new Registered($user));

        // Redirect to verify email page
        return redirect()->route('verification.notice');
    }
}
