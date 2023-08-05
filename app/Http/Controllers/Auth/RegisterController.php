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

    public function index(): Response
    {
        return response()->view('auth.register');
    }

    public function authenticate(RegisterRequest $request): RedirectResponse
    {
        $username = $request->validated('username');
        $email = $request->validated('email');
        $password = $request->validated('password');
        $remember = $request->validated('remember');

        $user = User::query()->create([
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        Auth::login($user, $remember);

        Mail::to($user)->queue(new RegisteredUser($user));
        event(new Registered($user));

        return redirect()->route('verification.notice');
    }
}
