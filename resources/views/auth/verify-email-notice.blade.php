@extends('layouts.app')
@section('content')

    Email Verification Sent! Check your email for a verification link.

    <form action="/auth/verify-email/resend" method="post" data-sitekey="{{ config('services.recaptcha.key') }}" data-action="reset-password">
        @csrf

        <input id="recaptcha_response" type="hidden" name="recaptcha_response">
        <input id="recaptcha_action" type="hidden" name="recaptcha_action">
        <button type="submit">Resend Verification Email</button>
    </form>

@stop
