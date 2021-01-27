<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        return view('home.dashboard');
    }

    // Maps
    public function maps()
    {
        return view('Maps.maps');
    }
}
