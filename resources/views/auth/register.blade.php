@extends('layouts.app')
@section('content')
    <h1 class="font-bold text-4xl text-center">Register</h1>

    <form class="w-full max-w-xs mx-auto space-y-8" action="/auth/register?@if($invitation?->email)redirect={{$redirect}}@endif" method="post" id="recaptcha-protected-form" data-sitekey="{{ config('services.recaptcha.key') }}" data-action="register">
        @csrf

        <div>
            <label class="block mb-3" for="username">
                Username
            </label>
            <input id="username" type="text" name="username" placeholder="Username" minlength="3" maxlength="20" required value="{{ old('username') }}" class="bg-gray-700 shadow-md appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none @if($errors->has('username')) border-red-400 @else border-gray-500 @endif">
            @if($errors->has('username'))
                <p class="mt-2 text-red-400">{{ $errors->first('username') }}</p>
            @endif
        </div>
        <div>
            <label class="block mb-3" for="email">
                Email
            </label>
            <input @if($invitation?->email) readonly @endif id="email" type="email" name="email" placeholder="Email" maxlength="255" required value="{{ $invitation?->email ?? old('email') }}" class="read-only:opacity-60 read-only:hover:cursor-not-allowed bg-gray-700 shadow-md appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none @if($errors->has('email')) border-red-400 @else border-gray-500 @endif">
            @if($errors->has('email'))
                <p class="mt-2 text-red-400">{{ $errors->first('email') }}</p>
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
        <div>
            <label class="block mb-3" for="password_confirmation">
                Confirm Password
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm Password" required maxlength="60" class="bg-gray-700 shadow-md appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none @if($errors->has('password')) border-red-400 @else border-gray-500 @endif">
        </div>
        <input id="recaptcha_response" type="hidden" name="recaptcha_response">
        <input id="recaptcha_action" type="hidden" name="recaptcha_action">
        <button class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded focus:outline-none" type="submit">
            Register
        </button>
        <div>
            <a class="text-sm text-blue-500 hover:text-blue-700" href="{{route('login')}}">
                Already have an account?
            </a>
        </div>
    </form>

@stop
