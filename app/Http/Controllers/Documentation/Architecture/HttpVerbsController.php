<?php

namespace App\Http\Controllers\Documentation\Architecture;

use App\Http\Controllers\Controller;

class HttpVerbsController extends Controller
{
    public function index()
    {
        return view('docs.architecture.http-verbs.index');
    }
}
