@extends('layouts.app')
@section('content')
    <h1 class="text-6xl text-center font-extrabold">Architecture</h1>
    <div class="w-full w-8/12 mx-auto space-y-8 ">
        <hr class="h-px my-8 bg-gray-400 border-0">
        <h2 class="mb-5 text-2xl font-extrabold leading-none tracking-tight text-gray-200 md:text-3xl lg:text-4xl">HTTP
            Verbs</h2>

        @include('docs.architecture.http-verbs.pages.GET')
        <hr class="h-px my-8 bg-gray-600 border-0">
        @include('docs.architecture.http-verbs.pages.POST')
        <hr class="h-px my-8 bg-gray-600 border-0">
        @include('docs.architecture.http-verbs.pages.PATCH')
        <hr class="h-px my-8 bg-gray-600 border-0">
        @include('docs.architecture.http-verbs.pages.PUT')
        <hr class="h-px my-8 bg-gray-600 border-0">
        @include('docs.architecture.http-verbs.pages.DELETE')
    </div>
@stop
