<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class landingpage extends Controller
{
    public function clientBooking()
    {
        return view('landing.landing');
    }
}
