@extends('layouts.app')
@section('content')

    Hey, {{ auth()->user()->username }}

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>

@stop
