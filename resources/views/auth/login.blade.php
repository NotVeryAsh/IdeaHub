@extends('layouts.app')
@section('content')
    <div class="w-full max-w-xs">
        <form action="/auth/login" method="post" id="recaptcha-protected-form" data-sitekey="{{ config('services.recaptcha.key') }}" data-action="login">
            <div class="mb-4">
                <label class="block text-sm font-bold mb-2" for="username">
                    Email or Username
                </label>
                <input placeholder="Email or Username" id="username" type="text" name="identifier" maxlength="255" required class="bg-gray-700 shadow border-gray-500 appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-bold mb-2" for="password">
                    Password
                </label>
                <input id="password" type="password" name="password" placeholder="Password" required maxlength="60" class="bg-gray-700 shadow appearance-none border border-red-400 rounded w-full py-2 px-3 mb-3 leading-tight focus:outline-none focus:shadow-outline">
                <p class="text-red-400">Please choose a password.</p>
            </div>
            <input id="recaptcha_response" type="hidden" name="recaptcha_response">
            <input id="recaptcha_action" type="hidden" name="recaptcha_action">
            <div class="mb-6">
                <div class="flex items-center">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Log In
                    </button>
                </div>
            </div>
            <div class="mb-6">
                <div class="flex items-center">
                    <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="{{route('password.email')}}">
                        Forgot Password?
                    </a>
                </div>
            </div>
        </form>
    </div>

@stop
