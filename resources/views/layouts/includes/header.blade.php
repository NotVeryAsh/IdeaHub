{{--<nav class="bg-gray-900">--}}
{{--    <div class="flex flex-wrap items-center justify-between mx-auto p-4">--}}
{{--        <a href="#" class="flex items-center">--}}
{{--            <img src="{{ asset('images/idea-hub-logo-minimal.jpg') }}" class="h-8 mr-3" alt="Idea Hub" />--}}
{{--            <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Idea Hub</span>--}}
{{--        </a>--}}
{{--        <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-default" aria-expanded="false">--}}
{{--            <span class="sr-only">Open main menu</span>--}}
{{--            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">--}}
{{--                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>--}}
{{--            </svg>--}}
{{--        </button>--}}
{{--        <div class="hidden w-full md:block md:w-auto" id="navbar-default">--}}
{{--            <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">--}}
{{--                <li>--}}
{{--                    <a href="{{ route('home') }}" class="block py-2 pl-3 pr-4 bg-blue-700 rounded md:bg-transparent md:p-0 @if(request()->routeIs('home')) text-blue-500 @endif" aria-current="page">Home</a>--}}
{{--                </li>--}}
{{--                <li>--}}
{{--                    <a href="{{ route('dashboard') }}" class="block py-2 pl-3 pr-4 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent @if(request()->routeIs('dashboard')) text-blue-500 @endif">Dashboard</a>--}}
{{--                </li>--}}
{{--                <li>--}}
{{--                    <a href="#" class="block py-2 pl-3 pr-4 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent @if(request()->routeIs('docs')) text-blue-500 @endif">Docs</a>--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--        </div>--}}

{{--<!-- TODO Change to use user's profile_picture if they have one -->--}}
{{--        @if(\Illuminate\Support\Facades\Auth::check())--}}
{{--        <img class="w-10 h-10 rounded-full ring-2 ring-blue-500" src="{{ asset('images/idea-hub-logo-minimal.jpg') }}" alt="Bordered avatar">--}}
{{--        @else--}}
{{--        <div class="p-1 rounded-full ring-2 ring-blue-500 relative inline-flex items-center justify-center w-10 h-10 overflow-hidden bg-gray-100 rounded-full dark:bg-gray-600">--}}
{{--            <span class="font-medium text-gray-600 dark:text-gray-300">JL</span>--}}
{{--        </div>--}}
{{--        @endif--}}
{{--    </div>--}}
{{--</nav>--}}


<nav class="flex items-center justify-between flex-wrap bg-gray-900 p-6">
    <div class="flex items-center flex-shrink-0 text-white mr-6">
        <img src="{{ asset('images/idea-hub-logo-minimal.jpg') }}" class="h-8 mr-3" alt="Idea Hub" />
        <span class="font-semibold text-xl tracking-tight">Idea Hub</span>
    </div>
    <div class="block lg:hidden">
        <button class="flex items-center px-3 py-2 border rounded hover:text-white hover:border-white">
            <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
        </button>
    </div>
    <div class="w-full block flex-grow lg:flex lg:items-center lg:w-auto">
        <div class="text-sm lg:flex-grow">
            <a href="#responsive-header" class="block mt-4 lg:inline-block lg:mt-0 mr-4">
                Docs
            </a>
            <a href="#responsive-header" class="block mt-4 lg:inline-block lg:mt-0 mr-4">
                Examples
            </a>
            <a href="#responsive-header" class="block mt-4 lg:inline-block lg:mt-0">
                Blog
            </a>
        </div>
        <div>
            <a href="#" class="inline-block text-sm px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:bg-white mt-4 lg:mt-0">Download</a>
        </div>
    </div>
</nav>
