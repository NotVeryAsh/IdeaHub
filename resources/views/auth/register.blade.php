@extends('layouts.app')
@section('content')

    Register

    <form action="/auth/register" method="post" id="recaptcha-protected-form" data-sitekey="{{ config('services.recaptcha.key') }}" data-action="register">
        @csrf

        <input type="text" name="username" placeholder="Username" minlength="3" maxlength="20" required>
        <input type="email" name="email" placeholder="Email" maxlength="255" required>
        <input type="password" name="password" placeholder="Password" required maxlength="60">
        <input type="password" name="password_confirmation" placeholder="Confirm Password" required maxlength="60">
        <input id="recaptcha_response" type="hidden" name="recaptcha_response">
        <input id="recaptcha_action" type="hidden" name="recaptcha_action">
        <button type="submit">Register</button>
    </form>

@stop
