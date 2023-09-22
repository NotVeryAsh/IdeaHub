<?php

namespace App\Http\Controllers\Documentation;

use App\Http\Controllers\Controller;

class DocumentationController extends Controller
{
    public function index()
    {
        return view('docs.index');
    }
}
