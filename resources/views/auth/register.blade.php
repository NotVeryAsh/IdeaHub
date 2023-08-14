@extends('layouts.app')
@section('content')

    Register

    <form action="/auth/register" method="post">
        @csrf

        <input type="text" name="username" placeholder="Username" minlength="3" maxlength="20" required>
        <input type="email" name="email" placeholder="Email" maxlength="255" required>
        <input type="password" name="password" placeholder="Password" required maxlength="60">
        <input type="password" name="password_confirmation" placeholder="Confirm Password" required maxlength="60">
        <button type="submit">Register</button>
    </form>

@stop
