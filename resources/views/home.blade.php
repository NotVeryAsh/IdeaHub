@extends('layouts.app')
@section('content')

    <h1 class="font-bold text-4xl text-center">Home</h1>
    <hr class="h-px my-8 mx-auto bg-gray-200 border-0 dark:bg-gray-700 w-5/12">

    <div class="text-center">
        Welcome to {{ config('app.name') }}
    </div>
@stop
