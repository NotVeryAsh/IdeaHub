@extends('layouts.app')
@section('content')

    Login
    <form action="/auth/login" method="post" id="#recaptcha-protected-form" data-sitekey="{{ config('services.recaptcha.key') }}" data-action="login">
        @csrf

        <input type="text" name="identifier" placeholder="Email or username" maxlength="255" required>
        <input type="password" name="password" placeholder="Password" required maxlength="60">
        <input id="recaptcha_response" type="hidden" name="recaptcha_response">
        <input id="recaptcha_action" type="hidden" name="recaptcha_action">
        <button type="submit">Login</button>
    </form>

@stop
