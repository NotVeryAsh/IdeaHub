@extends('layouts.app')
@section('content')

    Forgot Password

    <form action="/auth/forgot-password" method="post" id="recaptcha-protected-form" data-sitekey="{{ config('services.recaptcha.key') }}" data-action="forgot_password">
        @csrf
        <input type="email" name="email" placeholder="Email" required>
        <input id="recaptcha_response" type="hidden" name="recaptcha_response">
        <input id="recaptcha_action" type="hidden" name="recaptcha_action">

        <button type="submit">Resend Verification Email</button>
    </form>

@stop
