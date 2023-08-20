@extends('layouts.app')
@section('content')

    @if(Session::has('status'))
        <p class="mt-2 text-xl text-center">{{ Session::get('status') }}</p>
    @endif

    @if($viewing_self)
        <h1 class="text-xl text-center">Viewing your profile</h1>
    @endif

    <div class="flex items-center space-x-8 w-8/12 mx-auto">

        <img src="{{ asset('images/idea-hub-logo-minimal.jpg') }}" class="rounded-full w-32 h-32 mt-4" alt="Users' profile picture">

        <div class="flex flex-col flex-grow">

            <h1 class="text-4xl font-bold mt-2">{{ $user->username }}</h1>

            <h2 class="text-xl font-bold mt-2">{{ $user->first_name }} {{ $user->last_name }}</h2>
        </div>
        @if($viewing_self)
            <button class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded focus:outline-none" type="submit">
                <a href="{{route('profile.edit')}}">Edit Profile</a>
            </button>
        @endif
    </div>

    <hr class="h-px my-8 mx-auto bg-gray-200 border-0 dark:bg-gray-700 w-8/12">
@stop
