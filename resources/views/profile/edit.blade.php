@extends('layouts.app')
@section('content')

    <h1 class="font-bold text-4xl text-center">Edit Profile</h1>

    <form class="w-full max-w-xs mx-auto space-y-8" action="/profile" method="post">
        @method('patch')
        @csrf

        <div>
            <label class="block mb-3" for="email">
                Email
            </label>
            <input placeholder="Email" id="email" type="text" name="email" maxlength="255" required value="{{ old('email') }}" class="bg-gray-700 shadow-md appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none @if($errors->has('email')) border-red-400 @else border-gray-500 @endif">
            @if($errors->has('email'))
                <p class="mt-2 text-red-400">{{ $errors->first('email') }}</p>
            @endif
        </div>
        <div>
            <label class="block mb-3" for="username">
                Username
            </label>
            <input placeholder="Username" id="username" type="text" name="username" maxlength="255" required value="{{ old('username') }}" class="bg-gray-700 shadow-md appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none @if($errors->has('username')) border-red-400 @else border-gray-500 @endif">
            @if($errors->has('username'))
                <p class="mt-2 text-red-400">{{ $errors->first('username') }}</p>
            @endif
        </div>
        <div>
            <label class="block mb-3" for="first_name">
                First Name
            </label>
            <input placeholder="First Name" id="first_name" type="text" name="first_name" maxlength="255" required value="{{ old('first_name') }}" class="bg-gray-700 shadow-md appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none @if($errors->has('first_name')) border-red-400 @else border-gray-500 @endif">
            @if($errors->has('first_name'))
                <p class="mt-2 text-red-400">{{ $errors->first('first_name') }}</p>
            @endif
        </div>
        <div>
            <label class="block mb-3" for="last_name">
                Last Name
            </label>
            <input placeholder="Last Name" id="last_name" type="text" name="last_name" maxlength="255" required value="{{ old('last_name') }}" class="bg-gray-700 shadow-md appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none @if($errors->has('last_name')) border-red-400 @else border-gray-500 @endif">
            @if($errors->has('last_name'))
                <p class="mt-2 text-red-400">{{ $errors->first('last_name') }}</p>
            @endif
        </div>
        <div>
            <label class="block mb-3" for="password">
                Password
            </label>
            <input id="password" type="password" name="password" placeholder="Password" required maxlength="60" class="bg-gray-700 shadow-md appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none @if($errors->has('password')) border-red-400 @else border-gray-500 @endif">
            @if($errors->has('password'))
                <p class="mt-2 text-red-400">{{ $errors->first('password') }}</p>
            @endif
        </div>
        <button class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded focus:outline-none" type="submit">
            Save
        </button>
    </form>
@stop
