@extends('layouts.app')
@section('content')

    <h1 class="font-bold text-4xl text-center">Edit Profile</h1>

    <form id="remove-profile-picture-form" method="POST" action="{{ route('profile.profile-picture.delete') }}">
        @method('delete')
        @csrf
    </form>

        <div class="w-full max-w-xs mx-auto space-y-8">

        <img id="preview-image" @if($profilePicture) src="{{ asset("storage/$profilePicture") }}" @endif class="@if(!$profilePicture) hidden @endif object-cover ring-2 ring-blue-500 rounded-full w-40 h-40 mt-4 mx-auto" alt="User's profile picture" @if($profilePicture)data-original-image=" {{ asset("storage/$profilePicture") }} "@endif>

        <div id="default-profile-picture" class="@if($profilePicture) hidden @endif rounded-full w-40 h-40 mt-4 mx-auto p-1 ring-2 ring-blue-500 bg-gray-600 flex align-item items-center justify-center">
            <span class="font-medium text-6xl text-gray-300">{{ \App\Services\ProfilePictureService::getProfilePictureInitials() }}</span>
        </div>

        <div class="flex flex-col items-center justify-center w-full space-y-8">
            <button id="change-profile-picture" class="mx-auto bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded focus:outline-none" type="button">
                Change Profile Picture
            </button>
            @if($errors->has('profile_picture'))
                <p class="text-red-400">{{ $errors->first('profile_picture') }}</p>
            @endif
            @if(Session::has('status'))
                <p class="mt-2 text-m text-center">{{ Session::get('status') }}</p>
            @endif
            <div id="profile-picture-upload" class="w-full hidden">
                <div class="flex flex-row align-items-center justify-content-center justify-between">
                    <button id="profile-picture-save" class="hidden mb-8 w-5/12 mx-auto bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded focus:outline-none" type="button">
                        Save
                    </button>
                    <button id="profile-picture-remove" class="@if(!$profilePicture) hidden @endif mb-8 w-5/12 mx-auto bg-red-400 hover:bg-red-500 font-bold py-2 px-4 rounded focus:outline-none" type="button">
                        Remove
                    </button>
                </div>

                <label for="dropzone-file" class="mb-8 flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">

                    <form id="save-profile-picture-form" method="POST" action="{{ route('profile.profile-picture.update') }}" enctype="multipart/form-data">
                        @method('patch')
                        @csrf

                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPEG or GIF</p>
                        </div>
                        <input name="profile_picture" id="dropzone-file" type="file" class="hidden" accept="image/gif,image/jpeg,image/png,image/webp" required />

                    </form>
                </label>

                <div class="text-center">
                    <p class="mb-4">Defaults</p>
                    <div class="ring-2 ring-slate-700 py-4 rounded-lg">
                        @if(!$defaultProfilePictures->isEmpty())
                            <form id="default-profile-picture-form" action="{{ route('profile.default-profile-picture.select', $defaultProfilePictures->first()->id) }}" method="POST">
                            </form>
                            @csrf
                            @method('patch')

                            @php
                                $lastRowIndex = $defaultProfilePictures->count() - 5;
                            @endphp

                            @foreach ($defaultProfilePictures as $picture)
                                @php
                                    $newRow = ($loop->iteration % 4 == 1);
                                @endphp

                                @if($newRow)
                                    <div class="flex flex-row align-items-center justify-content-center @if($loop->iteration < $lastRowIndex) mb-6 @endif">
                                @endif

                                        <button class="mx-auto default-profile-picture-button" data-picture-id="{{$picture->id}}" type="submit">
                                            <img src="{{ asset("storage/$picture->path") }}" class="object-cover ring-2 ring-blue-500 rounded-full w-10 h-10" alt="Default Profile Picture">
                                        </button>

                                @if($loop->iteration % 4 == 0 || $loop->last)
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <form class="w-full max-w-xs mx-auto space-y-8" action="/profile" method="post" enctype="multipart/form-data">
            @method('patch')
            @csrf

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
    </div>
@stop
