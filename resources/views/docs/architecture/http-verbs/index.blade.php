@extends('layouts.app')
@section('content')
    <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-200 md:text-5xl lg:text-6xl">Architecture</h1>
    <hr class="h-px my-8 bg-gray-400 border-0">
    <h2 class="mb-5 text-2xl font-extrabold leading-none tracking-tight text-gray-200 md:text-3xl lg:text-4xl">HTTP Verbs</h2>

    <!-- GET Verb -->
    @include('docs.architecture.http-verbs.GET')

@stop
