@extends('layouts.app')
@section('content')

    Email Verification Sent! Check your email for a verification link.

    <form action="/auth/verify-email/resend" method="post">
        @csrf
        <button type="submit">Resend Verification Email</button>
    </form>

@stop
