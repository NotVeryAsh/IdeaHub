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
    <h1 class="font-bold text-3xl text-center">Your Teams</h1>

    <div class="space-y-5">
        <table class="table-fixed ring-2 ring-slate-700 py-4 rounded-lg w-8/12 mx-auto">
            <thead class="">
            <tr class="text-left">
                <th class="w-10 py-6"></th>
                <th>
                    Name
                </th>
                <th class="py-6">
                    Amount of Members
                </th>
                <th class="py-6">
                    Creator
                </th>
                <th class="py-6">

                </th>
                <th class="w-10"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($ownedTeams as $team)
                <tr class="border-t-2 border-slate-700 @if(!$loop->last) border-b-2 @endif">
                    <td class="py-5"></td>
                    <td class="py-5"><p>{{$team->name}}</p></td>
                    <td class="py-5"><p>{{$team->members_count}} Members</p></td>
                    <td class="py-5"><p>{{$team->creator->username}}</p></td>
                    <td class="py-5">
                        <form>
                            <button onclick="">
                                Copy Link <i class="pl-1 fa-solid fa-link"></i>
                            </button>
                        </form>
                    <td class="py-5">
                        <button data-collapse-toggle="team-{{$team->id}}-collapsable">
                            <i class="text-xl fa-solid fa-ellipsis-vertical"></i>
                        </button>
                        <div id="team-{{$team->id}}-collapsable" class="hidden w-7/12 lg:w-5/12 xl:w-4/12 absolute flex-column justify-content-center align-items-center">
                            <ul class="font-medium flex flex-col p-4 mt-4 border rounded-lg bg-gray-800 border-gray-700">
                                @if($team->creator->is(Auth::user()))
                                    <li>
                                        <form action="{{route('teams.delete', $team)}}" method="POST" class="block py-3 pl-3 pr-4 rounded hover:bg-gray-700 text-red-500">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-left w-full" aria-current="page">Delete</button>
                                        </form>
                                    </li>
                                    <li>
                                        <a href="{{route('teams.show', $team)}}" class="block py-3 pl-3 pr-4 rounded hover:bg-gray-700 hover:text-blue-500" aria-current="page">View</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <hr class="h-px my-8 mx-auto bg-gray-200 border-0 dark:bg-gray-700 w-8/12">

    <h1 class="font-bold text-3xl text-center">Teams You're In</h1>


    <div class="space-y-5">
        <table class="table-fixed ring-2 ring-slate-700 py-4 rounded-lg w-8/12 mx-auto">
            <thead class="">
            <tr class="text-left">
                <th class="w-10 py-6"></th>
                <th>
                    Name
                </th>
                <th class="py-6">
                    Amount of Members
                </th>
                <th class="py-6">
                    Creator
                </th>
                <th class="py-6">

                </th>
                <th class="w-10"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($teams as $team)
                <tr class="border-t-2 border-slate-700 @if(!$loop->last) border-b-2 @endif">
                    <td class="py-5"></td>
                    <td class="py-5"><p>{{$team->name}}</p></td>
                    <td class="py-5"><p>{{$team->members_count}} Members</p></td>
                    <td class="py-5"><p>{{$team->creator->username}}</p></td>
                    <td class="py-5">
                        <form>
                            <button onclick="">
                                Copy Link <i class="pl-1 fa-solid fa-link"></i>
                            </button>
                        </form>
                    <td class="py-5">
                        <button data-collapse-toggle="team-{{$team->id}}-collapsable">
                            <i class="text-xl fa-solid fa-ellipsis-vertical"></i>
                        </button>
                        <div id="team-{{$team->id}}-collapsable" class="hidden w-7/12 lg:w-5/12 xl:w-4/12 absolute flex-column justify-content-center align-items-center">
                            <ul class="font-medium flex flex-col p-4 mt-4 border rounded-lg bg-gray-800 border-gray-700">
                                <li>
                                    <a href="{{route('teams.show', $team)}}" class="block py-3 pl-3 pr-4 rounded hover:bg-gray-700 hover:text-blue-500" aria-current="page">View</a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <hr class="h-px my-8 mx-auto bg-gray-200 border-0 dark:bg-gray-700 w-8/12">
@stop
