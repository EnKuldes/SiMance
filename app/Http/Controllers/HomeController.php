<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard');
    }
    public function dashboard()
    {
        return view('dashboard');
    }
    public function cc_147()
    {
        return view('cc-147');
    }
    public function digital_media()
    {
        return view('digital-media');
    }
    public function c4()
    {
        return view('c4');
    }
    public function myindihome()
    {
        return view('myindihome');
    }
}
