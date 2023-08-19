<!doctype html>
<html>
    <head>
        @include('layouts.includes.head')
    </head>
    <body class="bg-slate-800 text-slate-100 space-y-10">
        <header>
            @include('layouts.includes.header')
        </header>
        @yield('content')
        <footer>
            @include('layouts.includes.footer')
        </footer>
    </body>
</html>
