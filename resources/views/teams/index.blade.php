@extends('layouts.app')
@section('content')

    @if(Session::has('status'))
        <p class="mt-2 text-xl text-center">{{ Session::get('status') }}</p>
    @endif

    <div class="flex items-center space-x-8 w-8/12 mx-auto">
        <button class="ml-auto bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded focus:outline-none" type="submit">
            Create Team
        </button>
    </div>

    <h1 class="font-bold text-3xl text-center">Your Teams</h1>

    <div class="flex items-center space-x-8 w-8/12 mx-auto">
        <div class="flex-grow flex w-auto flex-row ring-2 ring-slate-700 py-4 rounded-lg items-center px-3 space-x-5">
            <div class="flex flex-grow flex-row space-x-5">
                <p>Team One</p>
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
    </div>
    <div class="flex items-center space-x-8 w-8/12 mx-auto">
        <div class="flex-grow flex w-auto flex-row ring-2 ring-slate-700 py-4 rounded-lg items-center px-3 space-x-5">
            <div class="flex flex-grow flex-row space-x-5">
                <p>Team One</p>
                <p>82 Members</p>
                <p>Copy Link <i class="pl-1 fa-solid fa-link"></i></p>
            </div>
            <i class="text-xl fa-solid fa-ellipsis-vertical"></i>
        </div>
    </div>

    <hr class="h-px my-8 mx-auto bg-gray-200 border-0 dark:bg-gray-700 w-8/12">

    <h1 class="font-bold text-3xl text-center">Teams you're in</h1>

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
