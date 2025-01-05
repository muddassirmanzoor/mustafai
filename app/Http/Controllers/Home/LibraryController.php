<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    public function library(Request $request)
    {
        return view('home.home-pages.library-albums');
    }
}
