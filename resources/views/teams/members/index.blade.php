@extends('layouts.app')
@section('content')

    @if(Session::has('status'))
        <p class="mt-2 text-xl text-center">{{ Session::get('status') }}</p>
    @endif

    @if($team->creator->is(Auth::user()))
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
    <h1 class="font-bold text-3xl text-center">Members</h1>

    <div class="space-y-5">
        <table class="table-fixed ring-2 ring-slate-700 py-4 rounded-lg w-8/12 mx-auto">
            <thead class="border-b-2 border-slate-700">
                <tr class="text-left">
                    <th class="w-20"></th>
                    <th class="py-6">
                        <a href="{{route('teams.members', ['team' => $team, 'order_by' => 'name', 'order_by_direction' => $orderByDirection === 'asc' ? 'desc' : 'asc'])}}">
                            Name
                            @if($orderBy === 'name')
                                <i class="fa-solid {{ $orderByDirection === 'asc' ? 'fa-angle-down': 'fa-angle-up' }} fa-2xs"></i>
                            @endif
                        </a>
                    </th>
                    <th class="py-6">
                        <a href="{{route('teams.members', ['team' => $team, 'order_by' => 'username', 'order_by_direction' => $orderByDirection === 'asc' ? 'desc' : 'asc'])}}">
                            Username
                            @if($orderBy === 'username')
                                <i class="fa-solid {{ $orderByDirection === 'asc' ? 'fa-angle-down': 'fa-angle-up' }} fa-2xs"></i>
                            @endif
                        </a>
                    </th>
                    <th class="py-6">
                        <a href="{{route('teams.members', ['team' => $team, 'order_by' => 'email', 'order_by_direction' => $orderByDirection === 'asc' ? 'desc' : 'asc'])}}">
                        Email
                            @if($orderBy === 'email')
                                <i class="fa-solid {{ $orderByDirection === 'asc' ? 'fa-angle-down': 'fa-angle-up' }} fa-2xs"></i>
                            @endif
                        </a>
                    </th>
                    <th class="py-6">
                        <a href="{{route('teams.members', ['team' => $team, 'order_by' => 'date_joined', 'order_by_direction' => $orderByDirection === 'asc' ? 'desc' : 'asc'])}}">
                            Date Joined
                            @if($orderBy === 'date_joined')
                                <i class="fa-solid {{ $orderByDirection === 'asc' ? 'fa-angle-down': 'fa-angle-up' }} fa-2xs"></i>
                            @endif
                        </a>
                    </th>
                    <th class="w-10"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $member)
                    <tr @if(!$loop->last)class="border-b-2 border-slate-700"@endif>
                        <td class="py-5">
                            @if($member->profile_picture)
                                <img class="mx-auto object-cover w-10 h-10 rounded-full ring-2 ring-blue-500 mx-auto" src="{{ asset("storage/$member->profile_picture") }}" alt="Bordered avatar">
                            @else
                                <div class="mx-auto p-1 ring-2 ring-blue-500 flex items-center justify-center w-10 h-10 overflow-hidden rounded-full bg-gray-600">
                                    <span class="font-medium text-gray-300">{{ \App\Services\ProfilePictureService::getProfilePictureInitials($member) }}</span>
                                </div>
                            @endif
                        </td>
                        <td class="py-5"><p>{{$member->first_name}} {{$member->last_name}}</p></td>
                        <td class="py-5"><p>{{$member->username}}</p></td>
                        <td class="py-5"><p>{{$member->email}}</p></td>
                        <td class="py-5"><p>{{$member->pivot->created_at}}</p></td>
                        <td class="py-5">
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
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <hr class="h-px my-8 mx-auto bg-gray-200 border-0 dark:bg-gray-700 w-8/12">

    <h1 class="font-bold text-3xl text-center">Invitations</h1>

    <div class="space-y-5">
        <table class="table-fixed ring-2 ring-slate-700 py-4 rounded-lg w-8/12 mx-auto">
            <thead class="border-b-2 border-slate-700">
            <tr class="text-left">
                <th class="w-10 py-6"></th>
                <th>
                    Email
                </th>
                <th class="py-6">
                    Date Invited
                </th>
                <th class="w-10"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($invitations as $invitation)
                <tr @if(!$loop->last)class="border-b-2 border-slate-700"@endif>
                    <td class="py-5"></td>
                    <td class="py-5"><p>{{$invitation->email}}</p></td>
                    <td class="py-5"><p>{{$invitation->created_at}}</p></td>
                    <td class="py-5">
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
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <hr class="h-px my-8 mx-auto bg-gray-200 border-0 dark:bg-gray-700 w-8/12">
@stop
