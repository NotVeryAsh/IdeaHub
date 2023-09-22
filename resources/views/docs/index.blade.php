@extends('layouts.app')
@section('content')
    <h1 class="text-6xl text-center font-extrabold">Docs</h1>
    <div class="w-full w-8/12 mx-auto space-y-8 ">
        <hr class="h-px my-8 bg-gray-400 border-0">
        <h2 class="mb-5 text-2xl font-extrabold leading-none tracking-tight text-gray-200 md:text-3xl lg:text-4xl">Basics</h2>

        <ul class="list-none">
            <li>- <a href="{{route('docs.architecture.request-lifecycle')}}">HTTP Verbs</a></li>
        </ul>

        <h2 class="mb-5 text-2xl font-extrabold leading-none tracking-tight text-gray-200 md:text-3xl lg:text-4xl">Laravel</h2>

        <ul class="list-none">
            <li>- <a href="{{route('docs.architecture.request-lifecycle')}}">Request Lifecycle</a></li>
        </ul>
    </div>
@stop
