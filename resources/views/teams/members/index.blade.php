@extends('layouts.app')
@section('content')

    @if(Session::has('status'))
        <p class="mt-2 text-xl text-center">{{ Session::get('status') }}</p>
    @endif

    <div class="flex items-center space-x-8 space-y-20 w-8/12 mx-auto relative">
        <button data-collapse-toggle="create-team-dropdown" class="ml-auto bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded focus:outline-none" type="submit">
            Create Team
        </button>
        <div id="create-team-dropdown" class="right-0 z-10 @if(!$errors->has('name')) hidden @endif w-7/12 lg:w-5/12 xl:w-4/12 absolute mt-20 flex-column justify-content-center align-items-center">
            <form action="/teams" method="post">
                @csrf
                <ul class="font-medium flex flex-col p-4 mt-4 border rounded-lg bg-gray-800 border-gray-700 space-y-3">
                    <li>
                        <input id="name" type="text" name="name" placeholder="Team Name" minlength="3" maxlength="50" required value="{{ old('name') }}" class="bg-gray-700 shadow-md appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none @if($errors->has('name')) border-red-400 @else border-gray-500 @endif">
                        @if($errors->has('name'))
                            <p class="mt-2 text-red-400">{{ $errors->first('name') }}</p>
                        @endif
                    </li>
                    <li>
                        <button class="ml-auto bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded focus:outline-none" type="submit">
                            Create Team
                        </button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <h1 class="font-bold text-3xl text-center">Members</h1>

    <div class="space-y-5">
        @foreach($members as $member)
            <a href="/teams/{{$team->id}}/members/{{$member->id}}" class="flex items-center w-8/12 mx-auto">
                <div class="flex-grow flex w-auto flex-row ring-2 ring-slate-700 py-4 rounded-lg items-center space-x-5 px-5">
                    <div class="flex flex-grow flex-row items-center">
                        <div>
                            @if($member->profile_picture)
                                <img class="object-cover w-10 h-10 rounded-full ring-2 ring-blue-500 mx-auto" src="{{ asset("storage/$member->profile_picture") }}" alt="Bordered avatar">
                            @else
                                <div class="p-1 ring-2 ring-blue-500 flex items-center justify-center w-10 h-10 overflow-hidden rounded-full bg-gray-600">
                                    <span class="font-medium text-gray-300">{{ \App\Services\ProfilePictureService::getProfilePictureInitials($member) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex space-x-5">
                            <p>{{$member->first_name}}</p>
                            <p>{{$member->last_name}}</p>
                            <p>{{$member->username}}</p>
                            <p>{{$member->email}}</p>
                        </div>
                        <form>

                        </form>
                    </div>
                    <button data-collapse-toggle="team-1-collapsable">
                        <i class="text-xl fa-solid fa-ellipsis-vertical"></i>
                    </button>
                    <div id="team-1-collapsable" class="hidden w-7/12 lg:w-5/12 xl:w-4/12 absolute mt-20 flex-column justify-content-center align-items-center">
                        <ul class="font-medium flex flex-col p-4 mt-4 border rounded-lg bg-gray-800 border-gray-700">
                            <li>
                                <button>...</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <hr class="h-px my-8 mx-auto bg-gray-200 border-0 dark:bg-gray-700 w-8/12">

    <h1 class="font-bold text-3xl text-center">Invitations</h1>

    <div class="space-y-5">
        @foreach($invitations as $invitation)
            <div class="flex items-center space-x-8 w-8/12 mx-auto">
                <div class="flex-grow flex w-auto flex-row ring-2 ring-slate-700 py-4 rounded-lg items-center px-5 space-x-5">
                    <div class="flex flex-grow flex-row space-x-5 items-center">
                        <p>{{$invitation->email}}</p>
                        <p>{{$invitation->expires_at}}</p>
                        <form>

                        </form>
                    </div>
                    <button data-collapse-toggle="team-1-collapsable">
                        <i class="text-xl fa-solid fa-ellipsis-vertical"></i>
                    </button>
                    <div id="team-1-collapsable" class="hidden w-7/12 lg:w-5/12 xl:w-4/12 absolute mt-20 flex-column justify-content-center align-items-center">
                        <ul class="font-medium flex flex-col p-4 mt-4 border rounded-lg bg-gray-800 border-gray-700">
                            <li>
                                <button>...</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <hr class="h-px my-8 mx-auto bg-gray-200 border-0 dark:bg-gray-700 w-8/12">
@stop
