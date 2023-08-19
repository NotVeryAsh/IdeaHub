@extends('layouts.app')
@section('content')

    <h1 class="font-bold text-4xl text-center">Forgot Password</h1>

    <form class="w-full max-w-xs mx-auto space-y-8" action="/auth/forgot-password" method="post" id="recaptcha-protected-form" data-sitekey="{{ config('services.recaptcha.key') }}" data-action="forgot_password">
        @csrf

        <div>
            <label class="block mb-3" for="email">
                Email
            </label>
        <input id="email" type="email" name="email" placeholder="Email" required class="bg-gray-700 shadow-md appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none @if($errors->has('email')) border-red-400 @else border-gray-500 @endif">
            @if($errors->has('email'))
                <p class="mt-2 text-red-400">{{ $errors->first('email') }}</p>
            @endif
        </div>
        <input id="recaptcha_response" type="hidden" name="recaptcha_response">
        <input id="recaptcha_action" type="hidden" name="recaptcha_action">

        <button class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded focus:outline-none" type="submit">
            Resend Verification Email
        </button>
    </form>

@stop
