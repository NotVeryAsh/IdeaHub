<nav class="border-gray-200 bg-gray-900 px-10">
    <div class="flex flex-wrap items-center mx-auto p-4">
        <a href="{{ route('home') }}" class="flex items-center">
            <img src="{{ asset('images/idea-hub-logo-minimal.jpg') }}" class="h-8 mr-3" alt="Idea Hub" />
            <span class="self-center text-2xl font-semibold whitespace-nowrap text-white">Idea Hub</span>
        </a>
        <div class="flex flex-wrap items-center space-x-8 ml-auto p-4">
            <button data-collapse-toggle="navbar-default" type="button" class="ml-auto inline-flex items-center p-2 w-10 h-10 justify-center text-sm rounded-lg md:hidden focus:outline-none focus:ring-2 text-gray-400 hover:bg-gray-700 focus:ring-gray-600" aria-controls="navbar-default" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                </svg>
            </button>
            <div id="navbar-default" class="hidden w-full md:block md:w-auto md:relative md:mt-0 absolute top-0 right-0 mt-20 flex-column justify-content-center align-items-center">
                <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 border rounded-lg md:flex-row md:space-x-8 md:mt-0 md:border-0 bg-gray-800 md:bg-gray-900 border-gray-700">
                    <li>
                        <a href="{{ route('home') }}" class="block py-3 pl-3 pr-4 rounded md:bg-transparent md:p-0 hover:text-blue-500 hover:bg-gray-700 md:hover:bg-transparent @if(request()->routeIs('home')) text-blue-500 @endif" aria-current="page">Home</a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard') }}" class="block py-3 pl-3 pr-4 rounded md:border-0 md:p-0 hover:text-blue-500 hover:bg-gray-700 md:hover:bg-transparent @if(request()->routeIs('dashboard')) text-blue-500 @endif">Dashboard</a>
                    </li>
                    <li>
                        <a href="#" class="block py-3 pl-3 pr-4 rounded md:border-0 md:p-0 hover:text-blue-500 hover:bg-gray-700 md:hover:bg-transparent @if(request()->routeIs('docs')) text-blue-500 @endif">Docs</a>
                    </li>
                </ul>
            </div>
            <button data-collapse-toggle="navbar-profile">
                @if(Auth::user() && Auth::user()->profile_picture)
                    <img class="w-25 h-10 rounded-full ring-2 ring-blue-500" src="{{ asset("storage/$profilePicture") }}" alt="Bordered avatar">
                @else
                    <div class="p-1 ring-2 ring-blue-500 flex items-center justify-center w-10 h-10 overflow-hidden rounded-full bg-gray-600">
                        <span class="font-medium text-gray-300">{{ \App\Services\ProfilePictureService::getProfilePictureInitials() }}</span>
                    </div>
                @endif
            </button>
            <div id="navbar-profile" class="hidden w-5/12 md:w-4/12 lg:w-3/12 xl:w-2/12 absolute top-0 right-0 mt-20 flex-column justify-content-center align-items-center">
                <ul class="font-medium flex flex-col p-4 mt-4 border rounded-lg bg-gray-800 border-gray-700">
                    <li>
                        @if(Auth::user())
                            <a href="{{ route('profile') }}" class="block py-3 pl-3 pr-4 rounded hover:bg-gray-700 hover:text-blue-500 @if(request()->routeIs('profile')) text-blue-500 @endif" aria-current="page">Profile</a>
                            <form action="{{ route('logout') }}" method="POST" class="block py-3 pl-3 pr-4 rounded hover:bg-gray-700 hover:text-blue-500">
                                @csrf
                                <button type="submit" class="text-left w-full" aria-current="page">Log Out</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block py-3 pl-3 pr-4 rounded hover:bg-gray-700 hover:text-blue-500 @if(request()->routeIs('login')) text-blue-500 @endif" aria-current="page">Log In</a>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
