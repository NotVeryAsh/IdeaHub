@extends('layouts.app')
@section('content')

    @if(Session::has('status'))
        <p class="mt-2 text-xl text-center">{{ Session::get('status') }}</p>
    @endif

    <div class="flex items-center space-x-8 w-8/12 mx-auto">
        <button class="ml-auto bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded focus:outline-none" type="submit">
            Create New Team
        </button>
    </div>

    <h1 class="text-xl text-center">Your Teams</h1>

    <div class="items-center space-x-8 w-8/12 mx-auto">
        <a href="" class="flex flex-row ring-2 ring-slate-700 py-4 rounded-lg items-center px-3 space-x-5">
            <p>Team Name</p>
            <button class="bg-red-500 hover:bg-red-700 font-bold py-2 px-4 rounded focus:outline-none" type="submit">
                Leave Team
            </button>
        </a>
    </div>

    <hr class="h-px my-8 mx-auto bg-gray-200 border-0 dark:bg-gray-700 w-8/12">

    <h1 class="text-xl text-center">Teams you're in</h1>
    <div class="flex items-center space-x-8 w-8/12 mx-auto">
        <div class="ring-2 ring-slate-700 py-4 rounded-lg">
        </div>
    </div>

    <hr class="h-px my-8 mx-auto bg-gray-200 border-0 dark:bg-gray-700 w-8/12">
@stop
