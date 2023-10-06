@extends('layouts.app')
@section('content')

    @if(Session::has('status'))
        <p class="mt-2 text-xl text-center">{{ Session::get('status') }}</p>
    @endif

    @if(Auth::id() === $team->creator_id)
        <div class="flex items-center space-x-8 space-y-20 w-8/12 mx-auto relative">
            <button data-collapse-toggle="create-team-dropdown" class="ml-auto bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded focus:outline-none" type="submit">
                Invite
            </button>
            <div id="create-team-dropdown" class="right-0 z-10 @if(!$errors->has('email')) hidden @endif w-7/12 lg:w-5/12 xl:w-4/12 absolute mt-20 flex-column justify-content-center align-items-center">
                <form action="/teams/{{ $team->id }}/invitations" method="post">
                    @csrf
                    <ul class="font-medium flex flex-col p-4 mt-4 border rounded-lg bg-gray-800 border-gray-700 space-y-3">
                        <li>
                            <input id="email" type="email" name="email" placeholder="Email" maxlength="255" required value="{{ old('email') }}" class="bg-gray-700 shadow-md appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none @if($errors->has('email')) border-red-400 @else border-gray-500 @endif">
                            @if($errors->has('email'))
                                <p class="mt-2 text-red-400">{{ $errors->first('email') }}</p>
                            @endif
                        </li>
                        <li>
                            <button class="ml-auto bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded focus:outline-none" type="submit">
                                Send Invitation
                            </button>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
    @endif

    <h1 class="font-bold text-3xl text-center">{{$team->name}}</h1>

    <hr class="h-px my-8 mx-auto bg-gray-200 border-0 dark:bg-gray-700 w-8/12">
@stop
