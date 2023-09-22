<?php

namespace App\Http\Controllers\Documentation\Laravel;

use App\Http\Controllers\Controller;

class RequestLifecycleController extends Controller
{
    public function index()
    {
        return view('docs.laravel.request-lifecycle.index');
    }
}
