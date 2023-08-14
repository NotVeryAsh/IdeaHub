@extends('layouts.app')
@section('content')

    Login
    <form action="/auth/login" method="post">
        @csrf

        <input type="text" name="identifier" placeholder="Email or username" maxlength="255" required>
        <input type="password" name="password" placeholder="Password" required maxlength="60">
        <button type="submit">Login</button>
    </form>

@stop
