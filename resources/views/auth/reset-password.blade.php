@extends('layouts.app')
@section('content')

    <h1 class="font-bold text-4xl text-center">Reset Password</h1>

    <form class="w-full max-w-xs mx-auto space-y-8" action="/auth/reset-password" method="post" id="recaptcha-protected-form" data-sitekey="{{ config('services.recaptcha.key') }}" data-action="reset_password">
        @csrf
        <div>
            <label class="block mb-3" for="email">
                Email
            </label>
        <input id="email" readonly aria-readonly type="email" name="email" placeholder="Email" required value="{{ request()->get('email') }}" class="bg-gray-700 shadow-md appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none @if($errors->has('email')) border-red-400 @else border-gray-500 @endif">
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
        <input type="hidden" name="token" value="{{ $token }}">
        <input id="recaptcha_response" type="hidden" name="recaptcha_response">
        <input id="recaptcha_action" type="hidden" name="recaptcha_action">

        <button class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded focus:outline-none" type="submit">
            Reset Password
        </button>
    </form>

@stop
