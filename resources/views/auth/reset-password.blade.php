@extends('layouts.app')
@section('content')

    Reset Password

    <form action="/auth/reset-password" method="post" data-sitekey="{{ config('services.recaptcha.key') }}" data-action="reset-password">
        @csrf
        <input type="email" name="email" placeholder="Email" required>
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="password" name="password">
        <input type="password" name="password_confirmation">
        <input id="recaptcha_response" type="hidden" name="recaptcha_response">
        <input id="recaptcha_action" type="hidden" name="recaptcha_action">

        <button type="submit">Resend Verification Email</button>
    </form>

@stop
