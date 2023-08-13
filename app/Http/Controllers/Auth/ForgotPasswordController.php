<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    /**
     * Show forgot password page
     */
    public function index(): RedirectResponse|View
    {
        return view('auth.forgot-password');
    }

    public function sendNotification()
    {

    }
}
