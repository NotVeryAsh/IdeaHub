@extends('layouts.app')
@section('content')

    @if(Session::has('status'))
        <p class="mt-2 text-xl text-center">{{ Session::get('status') }}</p>
    @endif

    <div class="flex items-center space-x-8 space-y-20 w-8/12 mx-auto relative">
        <button data-collapse-toggle="create-team-dropdown" class="ml-auto bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded focus:outline-none" type="submit">
            Create Team
        </button>
        <div id="create-team-dropdown" class="right-0 z-10 hidden w-5/12 md:w-4/12 lg:w-3/12 xl:w-2/12 absolute mt-20 flex-column justify-content-center align-items-center">
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
    <h1 class="font-bold text-3xl text-center">Your Teams</h1>

    @foreach($teams as $team)
        <a href="/teams/{{$team->id}}" class="flex items-center space-x-8 w-8/12 mx-auto">
            <div class="flex-grow flex w-auto flex-row ring-2 ring-slate-700 py-4 rounded-lg items-center px-3 space-x-5">
                <div class="flex flex-grow flex-row space-x-5">
                    <p>{{$team->name}}</p>
                    <p>82 Members</p>
                    <form>
                        <button onclick="">
                            Copy Link <i class="pl-1 fa-solid fa-link"></i>
                        </button>
                    </form>
                </div>
                <button data-collapse-toggle="team-1-collapsable">
                    <i class="text-xl fa-solid fa-ellipsis-vertical"></i>
                </button>
                <div id="team-1-collapsable" class="hidden w-5/12 md:w-4/12 lg:w-3/12 xl:w-2/12 absolute mt-20 flex-column justify-content-center align-items-center">
                    <ul class="font-medium flex flex-col p-4 mt-4 border rounded-lg bg-gray-800 border-gray-700">
                        <li>
                            <button>...</button>
                        </li>
                    </ul>
                </div>
            </div>
        </a>
    @endforeach

    <hr class="h-px my-8 mx-auto bg-gray-200 border-0 dark:bg-gray-700 w-8/12">

    <h1 class="font-bold text-3xl text-center">Teams You're In</h1>

    <div class="flex items-center space-x-8 w-8/12 mx-auto">
        <div class="flex-grow flex w-auto flex-row ring-2 ring-slate-700 py-4 rounded-lg items-center px-3 space-x-5">
            <div class="flex flex-grow flex-row space-x-5">
                <p>Team One</p>
                <p>82 Members</p>
                <p>Copy Link <i class="pl-1 fa-solid fa-link"></i></p>
            </div>
            <div class="flex flex-row space-x-5 items-center">
                <button class="ml-auto bg-red-500 hover:bg-red-700 font-bold py-2 px-4 rounded outline-none">Leave</button>
            </div>
        </div>
    </div>
    <div class="flex items-center space-x-8 w-8/12 mx-auto">
        <div class="flex-grow flex w-auto flex-row ring-2 ring-slate-700 py-4 rounded-lg items-center px-3 space-x-5">
            <div class="flex flex-grow flex-row space-x-5">
                <p>Team One</p>
                <p>82 Members</p>
                <p>Copy Link <i class="pl-1 fa-solid fa-link"></i></p>
            </div>
            <div class="flex flex-row space-x-5 items-center">
                <button class="ml-auto bg-red-500 hover:bg-red-700 font-bold py-2 px-4 rounded outline-none">Leave</button>
            </div>
        </div>
    </div>

    <hr class="h-px my-8 mx-auto bg-gray-200 border-0 dark:bg-gray-700 w-8/12">
@stop
