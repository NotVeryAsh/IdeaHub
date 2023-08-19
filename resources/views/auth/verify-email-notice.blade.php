@extends('layouts.app')
@section('content')
    <h1 class="font-bold text-4xl text-center">Verify Email</h1>

    @if(Session::has('status'))
        <p class="mt-2 text-xl text-center">{{ Session::get('status') }}</p>
    @endif

    <p class="mt-2 text-xl text-center">Please verify your email. Check your email for a verification link.</p>

    <form class="w-full max-w-xs mx-auto space- y-8" action="/auth/verify-email/resend" method="post" id="recaptcha-protected-form" data-sitekey="{{ config('services.recaptcha.key') }}" data-action="reset_password">
        @csrf

        <input id="recaptcha_response" type="hidden" name="recaptcha_response">
        <input id="recaptcha_action" type="hidden" name="recaptcha_action">
        <button class="flex items-center mx-auto bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded focus:outline-none" type="submit">
            Resend Verification Email
        </button>
    </form>

@stop
