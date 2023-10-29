@extends('layouts.app')
@section('content')

    <p id="status" class="mt-2 text-xl text-center">@if(Session::has('status')){{ Session::get('status') }}@endif</p>

    <div class="flex items-center space-x-8 w-8/12 mx-auto relative">
        <a href="{{route('teams.members', $team)}}" class="ml-auto bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded focus:outline-none">
            <button>
                    Members
            </button>
        </a>
        @if($team->creator->is(Auth::user()))
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

            @if($team->link && \Carbon\Carbon::parse($team->link->expires_at)->isFuture())
                <form action="{{route('links.show', $team)}}" method="get" class="axios-form">
                    @csrf
                    <button type="submit" class="ml-auto bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded focus:outline-none">
                        Copy Link <i class="pl-1 fa-solid fa-link"></i>
                    </button>
                </form>
            @else
                <form id="create-team-link" action="{{route('links.store', $team)}}" method="post" class="axios-form">
                    @csrf
                    <button type="submit" class="ml-auto bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded focus:outline-none">
                        Copy Link <i class="pl-1 fa-solid fa-link"></i>
                    </button>
                </form>
            @endif

            <button data-collapse-toggle="edit-team-dropdown" class="ml-auto bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded focus:outline-none" type="submit">
                Edit
            </button>
            <div id="edit-team-dropdown" class="right-0 z-10 @if(!$errors->has('name')) hidden @endif w-7/12 lg:w-5/12 xl:w-4/12 absolute mt-20 flex-column justify-content-center align-items-center">
                <form action="{{route('teams.update', $team)}}" method="post">
                    @csrf
                    @method('PATCH')
                    <ul class="font-medium flex flex-col p-4 mt-4 border rounded-lg bg-gray-800 border-gray-700 space-y-3">
                        <li>
                            <input id="name" type="text" name="name" placeholder="Team Name" minlength="3" maxlength="50" required value="{{ $team->name }}" class="bg-gray-700 shadow-md appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none @if($errors->has('name')) border-red-400 @else border-gray-500 @endif">
                            @if($errors->has('name'))
                                <p class="mt-2 text-red-400">{{ $errors->first('name') }}</p>
                            @endif
                        </li>
                        <li>
                            <button class="ml-auto bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded focus:outline-none" type="submit">
                                Save
                            </button>
                        </li>
                    </ul>
                </form>
            </div>
        @elseif(Auth::user())
            <form action="{{route('teams.leave', $team)}}" method="post" class="ml-auto bg-red-400 hover:bg-red-500 font-bold py-2 px-4 rounded focus:outline-none">
                @csrf
                @method('DELETE')
                <button type="submit">
                    Leave
                </button>
            </form>
        @endif
    </div>

    <h1 class="font-bold text-3xl text-center">{{$team->name}}</h1>

    <hr class="h-px my-8 mx-auto bg-gray-200 border-0 dark:bg-gray-700 w-8/12">
@stop
