@extends('layouts.app')
@section('content')
    <h1 class="font-bold text-4xl text-center">Login</h1>

    @if(Session::has('status'))
        <p class="mt-2 text-xl text-center">{{ Session::get('status') }}</p>
    @endif

    <form class="w-full max-w-xs mx-auto space-y-8" action="/auth/login?@if($redirect)redirect={{$redirect}}@endif" method="post" id="recaptcha-protected-form" data-sitekey="{{ config('services.recaptcha.key') }}" data-action="login">
        @csrf

        <div>
            <label class="block mb-3" for="username">
                Email or Username
            </label>
            <input @if($invitation?->email) readonly @endif placeholder="Email or Username" id="username" type="text" name="identifier" maxlength="255" required value="{{ $invitation?->email ?? old('identifier') }}" class="read-only:opacity-60 read-only:hover:cursor-not-allowed bg-gray-700 shadow-md appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none @if($errors->has('identifier')) border-red-400 @else border-gray-500 @endif">
            @if($errors->has('identifier'))
                <p class="mt-2 text-red-400">{{ $errors->first('identifier') }}</p>
            @endif
        </div>
        <div>
            <label class="block mb-3" for="password">
                Password
            </label>
            <input id="password" type="password" name="password" placeholder="Password" required maxlength="60" class="bg-gray-700 shadow-md appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none @if($errors->has('password')) border-red-400 @else border-gray-500 @endif">
            @if($errors->has('password'))
                <p class="mt-2 text-red-400">{{ $errors->first('password') }}</p>
            @endif
        </div>
        <div class="flex items-center">
            <input id="remember" type="checkbox" name="remember" class="w-4 h-4 border rounded focus:ring-0 focus:ring-offset-0 bg-gray-700">
            <label class="block ml-2" for="remember">
                Remember Me
            </label>
        </div>
        <input id="recaptcha_response" type="hidden" name="recaptcha_response">
        <input id="recaptcha_action" type="hidden" name="recaptcha_action">
        <button class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded focus:outline-none" type="submit">
            Log In
        </button>
        <div class="flex flex-col space-y-4">
            <a class="text-sm text-blue-500 hover:text-blue-700" href="{{route('register')}}">
                Don't have an account yet?
            </a>
            <a class="text-sm text-blue-500 hover:text-blue-700" href="{{route('forgot-password')}}">
                Forgot Password?
            </a>
        </div>
    </form>

@stop
