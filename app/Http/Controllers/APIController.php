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
            /*$new_requests[$i] = new \Illuminate\Http\Request();
            $new_requests[$i]->setMethod('POST');
            $new_requests[$i]->request->add(['select_parameter' => $select_parameter]);
            $new_requests[$i]->request->add(['select_formulasi' => $select_formulasi[$i]]);
            $new_requests[$i]->request->add(['input_date' => $request->input_date]);
            $new_requests[$i]->request->add(['value_formulasi' => $request->input($req_value[$i])]);*/
            // $new_requests[$i]->setContainer(app())->setRedirector(app(\Illuminate\Routing\Redirector::class))->validateResolved();

            $new_requests[$i] = new Request([
                'select_parameter'   => $select_parameter,
                'select_formulasi'  => $select_formulasi[$i],
                'input_date'  => $request->input_date,
                'value_formulasi'  => $request->input($req_value[$i]),
            ]);

        }
        if ($request->input('traffic') > 45000) {
            /*$new_requests[2] = new \Illuminate\Http\Request();
            $new_requests[2]->setMethod('POST');
            $new_requests[2]->request->add(['select_parameter' => $select_parameter]);
            $new_requests[2]->request->add(['select_formulasi' => $select_formulasi[1]]);
            $new_requests[2]->request->add(['input_date' => $request->input_date]);
            $new_requests[2]->request->add(['value_formulasi' => 5]);
            $new_requests[2]->request->add(['is_justifikasi' => 1]);
            $new_requests[2]->request->add(['note_anomaly' => "Optimum Capacity."]);*/
            // $new_requests[2]->setContainer(app())->setRedirector(app(\Illuminate\Routing\Redirector::class))->validateResolved();
            $new_requests[2] = new Request([
                'select_parameter'   => $select_parameter,
                'select_formulasi'  => $select_formulasi[1],
                'input_date'  => $request->input_date,
                'value_formulasi'  => 5,
                'is_justifikasi'  => 1,
                'note_anomaly'  => "Optimum Capacity.",
            ]);
        }
        for ($i=0; $i < count($new_requests); $i++) { 
            var_dump($new_requests[$i]->request);
            // return app()->call('App\Http\Controllers\HomeController@save_daily_input', [ $new_requests[$i]->request ]);
        }
        die();
        
        // return app()->call('App\Http\Controllers\HomeController@save_daily_input', [$request]);
    }

    /*public function save_daily_sl_sosmed_input_v2(Request $request)
    {
        $validation = $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
            'input_date' => 'required',
            'traffic' => 'required',
            'response_time' => 'required',
        ]);
        $select_parameter = 6;
        $select_formulasi = [48, 49];
        $req_params = ['traffic', 'response_time'];
        $req_value = [$request->input('traffic'), intval($request->input('response_time'))];
        if ($request->input('traffic') > 45000) {
            $req_params[] = 'response_time';
            $req_value[] = 5;
            $select_formulasi[] = 49;
        }
        
        for ($i=0; $i < count($req_value); $i++) { 
            if ($i < 2) {
                $response = Http::retry(1, 100)->post(route('post.save-daily-input'), [
                    'username' => $request->input('username'),
                    'password' => $request->input('password'),
                    'input_date' => $request->input('input_date'),
                    'select_parameter' => $select_parameter,
                    'select_formulasi' => $select_formulasi[$i],
                    'value_formulasi' => $req_value[$i],
                ]);
            }
            else{
                $response = Http::retry(1, 100)->post(route('post.save-daily-input'), [
                    'username' => $request->input('username'),
                    'password' => $request->input('password'),
                    'input_date' => $request->input('input_date'),
                    'select_parameter' => $select_parameter,
                    'select_formulasi' => $select_formulasi[$i],
                    'value_formulasi' => $req_value[$i],
                    'is_justifikasi' => 1,
                    'note_anomaly' => "Optimum Capacity."
                ]);
            }
            if ($response->successful()) {
                $result = json_decode($response->body(), true);
                // Cek error atau tidak paketnya
                if ($result['success']) {
                    // Do Nothing
                }
            }
            else{
                abort(500, 'Gagal Menyimpan Data!');
            }
        }
        return response()->json(['success' => "success"], 200);
    }*/

    public function save_daily_sl_sosmed_input_v2(Request $request)
    {
        $loginData = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        
        if (!auth()->once($loginData)) {
            return response()->json(['message' => "Invalid Credentials"], 400);
        }
        // Jika bukan user Level 1 (Bisa Input Daily dan Liat Dashboaard)
        if (auth()->user()->level != 1 && auth()->user()->layanan == 2) {
            return response()->json(['message' => "You don't have the authority to access this."], 401);
        }

        $validation = $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
            'input_date' => 'required',
            'traffic' => 'required',
            'response_time' => 'required',
        ]);
        $select_parameter = 6;
        $select_formulasi = [48, 49];
        $req_params = ['traffic', 'response_time'];
        $req_value = [intval($request->input('traffic')), intval($request->input('response_time'))];
        $countSuccess = 0;
        if ($request->input('traffic') > 45000) {
            $req_params[] = 'response_time';
            $req_value[] = 5;
            $select_formulasi[] = 49;
        }
        
        for ($i=0; $i < count($req_value); $i++) { 
            if ($i < 2) {
                $request->replace([
                    'username' => $request->input('username'),
                    'password' => $request->input('password'),
                    'input_date' => $request->input('input_date'),
                    'select_parameter' => $select_parameter,
                    'select_formulasi' => $select_formulasi[$i],
                    'value_formulasi' => $req_value[$i],
                ]);
            }
            else{
                $request->replace([
                    'username' => $request->input('username'),
                    'password' => $request->input('password'),
                    'input_date' => $request->input('input_date'),
                    'select_parameter' => $select_parameter,
                    'select_formulasi' => $select_formulasi[$i],
                    'value_formulasi' => $req_value[$i],
                    'is_justifikasi' => 1,
                    'note_anomaly' => "Optimum Capacity."
                ]);
            }
            $response = app()->call('App\Http\Controllers\HomeController@save_daily_input', [$request]);
            $result = $response->getData();
            if ($result->success) {
                $countSuccess++;
            }
            else{
                abort(500, 'Gagal Menyimpan Data!');
            }            
        }
        return response()->json(['success' => "success", "msg" => "Success added ".$countSuccess." data."], 200);
    }

    public function save_daily_sl_sosmed_input_v3(Request $request)
    {
        $loginData = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        
        if (!auth()->once($loginData)) {
            return response()->json(['message' => "Invalid Credentials"], 400);
        }
        // Jika bukan user Level 1 (Bisa Input Daily dan Liat Dashboaard)
        if (auth()->user()->level != 1 && auth()->user()->layanan == 2) {
            return response()->json(['message' => "You don't have the authority to access this."], 401);
        }

        $validation = $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
            'input_date' => 'required',
            'traffic' => 'required',
            'rt_fb' => 'required',
            'rt_tw' => 'required',
            'rt_email' => 'required',
            'rt_lc_wifiid' => 'required',
            'rt_lc_indihome' => 'required',
            'rt_ig' => 'required',
        ]);
        $select_parameter = 6;
        $select_formulasi = [48, 16, 17, 18, 19, 20, 47];
        $req_params = ['traffic', 'rt_fb', 'rt_tw', 'rt_email', 'rt_lc_wifiid', 'rt_lc_indihome', 'rt_ig'];
        $req_value = [];
        for ($i=0; $i < count($req_params); $i++) { 
            $req_value[] = intval($request->input($req_params[$i]));
        }
        $currVolume = count($req_value);
        $countSuccess = 0;
        if ($request->input('traffic') > 45000) {
            for ($i=1; $i < $currVolume; $i++) { 
                $req_params[] = $req_params[$i];
                $req_value[] = 5;
                $select_formulasi[] = $select_formulasi[$i];
            }
        }
        
        for ($i=0; $i < count($req_value); $i++) { 
            if ($i < $currVolume) {
                $request->replace([
                    'username' => $request->input('username'),
                    'password' => $request->input('password'),
                    'input_date' => $request->input('input_date'),
                    'select_parameter' => $select_parameter,
                    'select_formulasi' => $select_formulasi[$i],
                    'value_formulasi' => $req_value[$i],
                ]);
            }
            else{
                $request->replace([
                    'username' => $request->input('username'),
                    'password' => $request->input('password'),
                    'input_date' => $request->input('input_date'),
                    'select_parameter' => $select_parameter,
                    'select_formulasi' => $select_formulasi[$i],
                    'value_formulasi' => $req_value[$i],
                    'is_justifikasi' => 1,
                    'note_anomaly' => "Optimum Capacity."
                ]);
            }
            $response = app()->call('App\Http\Controllers\HomeController@save_daily_input', [$request]);
            $result = $response->getData();
            if ($result->success) {
                $countSuccess++;
            }
            else{
                abort(500, 'Gagal Menyimpan Data!');
            }            
        }
        return response()->json(['success' => "success", "msg" => "Success added ".$countSuccess." data."], 200);
    }
}
