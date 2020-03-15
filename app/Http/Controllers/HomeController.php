<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Koneksi via DB
use DB;
use App\daily_transaksi;

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
      $data['parameters_tab'] = DB::table('parameters')->select('id', 'parameter_desc')->where([
        ['is_enabled','=','1']
      ])->where('id_layanan', '=', auth()->user()->layanan)->get();

      return view('cc-147')->with('data',$data);;
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

    # Save Target, Bobot dan Satuan
    public function save_daily_input(Request $request)
    {
      $id_layanan = auth()->user()->layanan;
      $id_parameter = $request->select_parameter;
      $id_formulasi = $request->select_formulasi;

      $saveResult = daily_transaksi::updateOrCreate(
        [
          'id_layanan' => auth()->user()->layanan
          , 'id_parameter' => $request->select_parameter
          , 'id_formulasi' => $request->select_formulasi
          , 'tanggal' => now()->format('Y-m-d')
        ],
        [
          'nilai' => $request->value_formulasi
          , 'user_input' => auth()->user()->username
        ]
      );
      if (! daily_transaksi::findOrFail($saveResult->id)) {
        abort(500, 'Error while saving value.');
      }
      // Return hasilnya
      return response()->json(['success' => "success"], 200);
    }

    # Save KPI/target dan Bobot
    public function save_kpi(Request $request)
    {
      # code...
    }

    # Get KPI
    public function get_kpi(Request $request)
    {
      $datas = DB::table('kpi')
      ->leftJoin('parameters', 'parameters.id', '=', 'kpi.id_parameter')
      ->select('kpi.satuan', 'kpi.target', 'kpi.bobot', 'parameters.parameter_desc')->where([
        ['kpi.active', '=', 'current']
        , ['kpi.id_layanan', '=', auth()->user()->layanan]
        , ['kpi.id_parameter', '=', $request->id_parameter]
      ])->get();
      $datas->map(function ($datas, $i) {
        $datas->cur_target = $datas->target." ".$datas->satuan;
        $datas->cur_bobot = $datas->bobot." %";
        return $datas;
      });
      return response()->json($datas);
    }

    # Get Monthly Data
    public function get_monthly_data(Request $request)
    {
      $datas = DB::table('daily_transaksis')
      ->leftJoin('formulasis', 'daily_transaksis.id_formulasi', '=', 'formulasis.id')
      ->leftJoin('parameters', 'parameters.id', '=', 'formulasis.id_parameter')
      ->where([
        ['parameters.id_layanan', '=', auth()->user()->layanan]
      ])
      ->whereRaw('MONTH(daily_transaksis.tanggal) = MONTH(CURRENT_DATE()) AND YEAR(daily_transaksis.tanggal) = YEAR(CURRENT_DATE())')
      ->selectRaw(
        'DAY(daily_transaksis.tanggal) AS `day`
        , DATE_FORMAT(daily_transaksis.tanggal, "%Y-%m") AS `date`
        , daily_transaksis.nilai AS value_item
        , formulasis.formulasi_desc AS value_desc
        , parameters.parameter_desc AS parameter_desc'
      )
      ->get();
      return response()->json($datas);
    }

    # Chaining
    public function list_parameter(Request $request)
    {
      $datas = DB::table('parameters')->select('id', 'parameter_desc')->where([
        ['is_enabled','=','1']
      ]);
      if (auth()->user()->layanan !== null) {
          $datas->where('id_layanan', '=', auth()->user()->layanan);
      }
      $datas = $datas->get();
      return response()->json($datas);
    }
    public function list_formulasi(Request $request)
    {
      $datas = DB::table('formulasis')->select('id', 'formulasi_desc')->where([
        ['is_enabled','=','1']
      ]);
      if ( request()->ajax() ) {
        if (!empty($request->id)) {
          $datas->where('id', '=', $request->id);
        }
        elseif (!empty($request->id_parameter)) {
          $datas->where('id_parameter', '=', $request->id_parameter);
        }
      }
      $datas = $datas->get();
      return response()->json($datas);
    }
    public function list_date(Request $request)
    {
      $datas = DB::table('daily_transaksis')->where([
        ['id_layanan','=',auth()->user()->layanan]
      ]);
      if ( request()->ajax() ) {
        if (!empty($request->select_year)) {
          $datas->whereYear('tanggal', '=', $request->select_year)->select(DB::raw('MONTH(`tanggal`) as `month`'))->distinct()->orderBy('month', 'asc');
        }
        else {
          $datas->select(DB::raw('YEAR(`tanggal`) as `year`'))->distinct()->orderBy('year', 'desc');
        }
      }
      $datas = $datas->get();
      return response()->json($datas);
    }
}
