<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class APIController extends Controller
{
    public function save_daily_input(Request $request)
    {
    	$loginData = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (!auth()->once($loginData)) {
            return response()->json(['message' => "Invalid Credentials"], 400);
        }
        // Jika bukan user Level 1 (Bisa Input Daily dan Liat Dashboaard)
        if (auth()->user()->level != 1) {
        	return response()->json(['message' => "You don't have the authority to access this."], 401);
        }
        return app()->call('App\Http\Controllers\HomeController@save_daily_input', [$request]);
    }
}
