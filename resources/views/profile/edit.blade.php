@extends('layouts.app')
@section('content')

    <h1 class="font-bold text-4xl text-center">Edit Profile</h1>

    <form class="w-full max-w-xs mx-auto space-y-8" action="/profile" method="post" enctype="multipart/form-data">
        @method('patch')
        @csrf

        <img id="preview-image" src="{{ asset('images/idea-hub-logo-minimal.jpg') }}" class="rounded-full w-32 h-32 mt-4 mx-auto" alt="User's profile picture">

        <div class="flex flex-col items-center justify-center w-full space-y-8">
            <button id="change-profile-picture" class="mx-auto bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded focus:outline-none" type="button">
                Change Profile Picture
            </button>
            <div id="profile-picture-upload" class="w-full hidden">
                <div class="flex flex-row align-items-center justify-content-center justify-between">
                    <button id="profile-picture-save" class="hidden mb-8 w-5/12 mx-auto bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded focus:outline-none" type="button">
                        Save
                    </button>
                    <button id="profile-picture-remove" class="@if(!$user->profile_picture) hidden @endif mb-8 w-5/12 mx-auto bg-red-400 hover:bg-red-500 font-bold py-2 px-4 rounded focus:outline-none" type="button">
                        Remove
                    </button>
                </div>
                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                        </svg>
                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPEG or GIF</p>
                    </div>
                    <input name="profile_picture" id="dropzone-file" type="file" class="hidden" accept="image/gif,image/jpeg,image/png,image/webp" />
                </label>
            </div>
        </div>

        <div>
            <label class="block mb-3" for="email">
                Email
            </label>
            <input placeholder="Email" id="email" type="text" name="email" maxlength="255" required value="{{ $user->email }}" class="bg-gray-700 shadow-md appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none @if($errors->has('email')) border-red-400 @else border-gray-500 @endif">
            @if($errors->has('email'))
                <p class="mt-2 text-red-400">{{ $errors->first('email') }}</p>
            @endif
        </div>
        <div>
            <label class="block mb-3" for="username">
                Username
            </label>
            <input placeholder="Username" id="username" type="text" name="username" maxlength="255" required value="{{ $user->username }}" class="bg-gray-700 shadow-md appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none @if($errors->has('username')) border-red-400 @else border-gray-500 @endif">
            @if($errors->has('username'))
                <p class="mt-2 text-red-400">{{ $errors->first('username') }}</p>
            @endif
        </div>
        <div>
            <label class="block mb-3" for="first_name">
                First Name
            </label>
            <input placeholder="First Name" id="first_name" type="text" name="first_name" maxlength="255" value="{{ $user->first_name }}" class="bg-gray-700 shadow-md appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none @if($errors->has('first_name')) border-red-400 @else border-gray-500 @endif">
            @if($errors->has('first_name'))
                <p class="mt-2 text-red-400">{{ $errors->first('first_name') }}</p>
            @endif
        </div>
        <div>
            <label class="block mb-3" for="last_name">
                Last Name
            </label>
            <input placeholder="Last Name" id="last_name" type="text" name="last_name" maxlength="255" value="{{ $user->last_name }}" class="bg-gray-700 shadow-md appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none @if($errors->has('last_name')) border-red-400 @else border-gray-500 @endif">
            @if($errors->has('last_name'))
                <p class="mt-2 text-red-400">{{ $errors->first('last_name') }}</p>
            @endif
        </div>
        <div>
            <label class="block mb-3" for="password">
                Password
            </label>
            <input id="password" type="password" name="password" placeholder="Password" maxlength="60" class="bg-gray-700 shadow-md appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none @if($errors->has('password')) border-red-400 @else border-gray-500 @endif">
            @if($errors->has('password'))
                <p class="mt-2 text-red-400">{{ $errors->first('password') }}</p>
            @endif
        </div>
        <div>
            <label class="block mb-3" for="password_confirmation">
                Confirm Password
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm Password" maxlength="60" class="bg-gray-700 shadow-md appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none @if($errors->has('password')) border-red-400 @else border-gray-500 @endif">
        </div>
        <button class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded focus:outline-none" type="submit">
            Save
        </button>
    </form>
@stop
