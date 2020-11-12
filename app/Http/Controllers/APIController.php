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

    public function save_daily_sl_sosmed_input(Request $request)
    {
        $loginData = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        $dataRequired = $request->validate([
            'traffic' => 'required',
            'response_time' => 'required',
        ]);

        var_dump ($request->all());
        die();

        if (!auth()->once($loginData)) {
            return response()->json(['message' => "Invalid Credentials"], 400);
        }
        // Jika bukan user Level 1 (Bisa Input Daily dan Liat Dashboaard)
        if (auth()->user()->level != 1 && auth()->user()->layanan == 2) {
            return response()->json(['message' => "You don't have the authority to access this."], 401);
        }
        
        $select_parameter = 6;
        $select_formulasi = [48, 49];
        $req_value = ['traffic', 'response_time'];
        $new_requests = [];
        for ($i=0; $i < count($select_formulasi) ; $i++) { 
            $new_requests[$i] = new \Illuminate\Http\Request();
            $new_requests[$i]->setMethod('POST');
            $new_requests[$i]->request->add(['select_parameter' => $select_parameter]);
            $new_requests[$i]->request->add(['select_formulasi' => $select_formulasi[$i]]);
            $new_requests[$i]->request->add(['input_date' => $request->input_date]);
            $new_requests[$i]->request->add(['value_formulasi' => $request->input($req_value[$i])]);
        }
        if ($request->input('traffic') > 45000) {
            $new_requests[2] = new \Illuminate\Http\Request();
            $new_requests[2]->setMethod('POST');
            $new_requests[2]->request->add(['select_parameter' => $select_parameter]);
            $new_requests[2]->request->add(['select_formulasi' => $select_formulasi[1]]);
            $new_requests[2]->request->add(['input_date' => $request->input_date]);
            $new_requests[2]->request->add(['value_formulasi' => 5]);
            $new_requests[2]->request->add(['is_justifikasi' => 1]);
            $new_requests[2]->request->add(['note_anomaly' => "Optimum Capacity."]);
        }
        for ($i=0; $i < count($new_requests); $i++) { 
            var_dump ($new_requests[$i]->request->all());
            die();
            return app()->call('App\Http\Controllers\HomeController@save_daily_input', [ $new_requests[$i]->request ]);
        }
        
        // return app()->call('App\Http\Controllers\HomeController@save_daily_input', [$request]);
    }
}
