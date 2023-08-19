@extends('layouts.app')
@section('content')

    <h1 class="font-bold text-4xl text-center">Edit Profile</h1>
    <hr class="h-px my-8 mx-auto bg-gray-200 border-0 dark:bg-gray-700 w-5/12">

    @if(Session::has('status'))
        <p class="mt-2 text-xl text-center">{{ Session::get('status') }}</p>
    @endif
@stop
