<!doctype html>
<html>
<head>
    @include('layouts.includes.head')
</head>
<body class="bg-slate-800 text-slate-100">
<header>
    @include('layouts.includes.header')
</header>
<div class="container mx-auto mt-5">
    @yield('content')
</div>
<footer class="row">
    @include('layouts.includes.footer')
</footer>
</body>
</html>
