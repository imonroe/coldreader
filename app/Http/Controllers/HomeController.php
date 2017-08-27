<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use imonroe\crps\Subject;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $homepage_aspects_config = Subject::where('name', '=', 'Front Page Aspects')->first();
        $homepage_aspects = $homepage_aspects_config->aspects();
        return view('home', ['homepage_aspects' => $homepage_aspects_config]);
    }
}
