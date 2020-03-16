<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Koneksi via DB
use DB;
use App\daily_transaksi;
use App\log_transaksi;

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
        $service_level = DB::table('log_transaksis')->where([
            ['layanan', '=', '1']
            , ['parameter', '=', '1']
        ])->orderBy('created_at', 'desc')->first();

        $fcr = DB::table('log_transaksis')->where([
            ['layanan', '=', '1']
            , ['parameter', '=', '2']
            //, ['log_date', '=', date('Y-m-d')]
        ])->orderBy('created_at', 'desc')->first();

        $rasio_sales = DB::table('log_transaksis')->where([
            ['layanan', '=', '1']
            , ['parameter', '=', '3']
        ])->orderBy('created_at', 'desc')->first();

        $ces = DB::table('log_transaksis')->where([
            ['layanan', '=', '1']
            , ['parameter', '=', '4']
        ])->orderBy('created_at', 'desc')->first();

        $quality_layanan = DB::table('log_transaksis')->where([
            ['layanan', '=', '1']
            , ['parameter', '=', '5']
        ])->orderBy('created_at', 'desc')->first();
        
        $bobot = DB::table('log_transaksis')->where([
            ['layanan', '=', '1']
        ])->orderBy('created_at', 'desc')->sum('persetasi_bobot');

        $count_bobot = DB::table('parameters')->where('id_layanan', '1')->count();

        $t_bobot = $bobot/$count_bobot;

        $percent = 100;
        $ttl_service_level_target_bobot = optional($service_level)->target + optional($service_level)->bobot;
        if($ttl_service_level_target_bobot != 0 && $ttl_service_level_target_bobot > 0){
            $target_service_level = (optional($service_level)->target/$ttl_service_level_target_bobot)*$percent;
            $bobot_service_level = $percent-$target_service_level;
        }else{
            $target_service_level= 0;
            $bobot_service_level= 0;
        }
        
        $ttl_fcr_target_bobot = optional($fcr)->target + optional($fcr)->bobot;
        if($ttl_fcr_target_bobot != 0 && $ttl_fcr_target_bobot > 0){
            $target_fcr = (optional($fcr)->target*$percent)/$ttl_fcr_target_bobot;
            $bobot_fcr = $percent-$target_fcr;
        }else{
            $target_fcr= 0;
            $bobot_fcr= 0;
        }
        
        $ttl_rasio_sales_target_bobot = optional($rasio_sales)->target + optional($rasio_sales)->bobot;
        if($ttl_rasio_sales_target_bobot != 0 && $ttl_rasio_sales_target_bobot > 0){
            $target_rasio_sales = (optional($rasio_sales)->target*$percent)/$ttl_rasio_sales_target_bobot;
            $bobot_rasio_sales = $percent-$target_rasio_sales;
        }else{
            $target_rasio_sales= 0;
            $bobot_rasio_sales= 0;
        }
        
        $ttl_ces_target_bobot = optional($ces)->target + optional($ces)->bobot;
        if($ttl_ces_target_bobot != 0 && $ttl_ces_target_bobot > 0){
            $target_ces = (optional($ces)->target*$percent)/$ttl_ces_target_bobot;
            $bobot_ces = $percent-$target_ces;
        }else{
            $target_ces= 0;
            $bobot_ces= 0;
        }
        
        $ttl_quality_layanan_target_bobot = optional($quality_layanan)->target + optional($quality_layanan)->bobot;
        if($ttl_quality_layanan_target_bobot != 0 && $ttl_quality_layanan_target_bobot > 0){
            $target_quality_layanan = (optional($quality_layanan)->target*$percent)/$ttl_quality_layanan_target_bobot;
            $bobot_quality_layanan = $percent-$target_quality_layanan;
        }else{
            $target_quality_layanan= 0;
            $bobot_quality_layanan= 0;
        }
            
        return view('dashboard',[
            'service_level' => $service_level,
            'fcr' => $fcr,
            'rasio_sales' => $rasio_sales,
            'ces' => $ces,
            'quality_layanan' => $quality_layanan,
            't_bobot' => $t_bobot,

            'target_service_level' => $target_service_level,
            'bobot_service_level' => $bobot_service_level,
            'target_fcr' => $target_fcr,
            'bobot_fcr' => $bobot_fcr,
            'target_rasio_sales' => $target_rasio_sales,
            'bobot_rasio_sales' => $bobot_rasio_sales,
            'target_ces' => $target_ces,
            'bobot_ces' => $bobot_ces,
            'target_quality_layanan' => $target_quality_layanan,
            'bobot_quality_layanan' => $bobot_quality_layanan
        ]);
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

      $lt = new log_transaksi;
      $lt->layanan = $id_layanan;
      $lt->parameter = $id_parameter;

      $kpi_model = DB::table('kpi')->where([
        ['id_layanan', '=', $id_layanan]
        , ['id_parameter', '=', $id_parameter]
        , ['active', '=', 'current']
      ])->first();

      $lt->satuan = $kpi_model->satuan;
      $lt->target = $kpi_model->target;
      $lt->bobot = $kpi_model->bobot;
      
      $dt = daily_transaksi::where([
        ['id_layanan', '=', $id_layanan]
        , ['id_parameter', '=', $id_parameter]
      ]);
      switch ($id_parameter) {
        case 1:
          $dt->selectRaw('SUM(CASE WHEN id_formulasi = 2 THEN nilai ELSE 0 END)/SUM(CASE WHEN id_formulasi = 1 THEN nilai ELSE 0 END)*100 AS realisasi');
          break;
        case 2:
          $dt->selectRaw('SUM(CASE WHEN id_formulasi = 4 THEN nilai ELSE 0 END)/SUM(nilai)*100 AS realisasi');
          break;
        case 3:
          $dt->selectRaw('SUM(CASE WHEN id_formulasi = 9 THEN nilai ELSE 0 END)/SUM(CASE WHEN id_formulasi = 8 THEN nilai ELSE 0 END)*100 AS realisasi');
          break;
        case 4:
          $dt->selectRaw('SUM(CASE WHEN id_formulasi = 11 THEN nilai ELSE 0 END)/SUM(nilai)*100 AS realisasi');
          break;
        case 5:
          $dt->selectRaw('SUM(CASE WHEN id_formulasi = 14 THEN nilai ELSE 0 END)/SUM(nilai)*100 AS realisasi');
          break;
        
        default:
          # code...
          break;
      }
      $dt = $dt->first();
      $realisasi = $dt->realisasi;
      $achievement = $realisasi/$kpi_model->target;
      $persetasi_bobot = $achievement*$kpi_model->bobot;

      $lt->realisasi = $realisasi; // Realisasi nyari dari data daily input per month

      $lt->achievement = $achievement; // Nilai Acgievments didapat dari Realiasi / Target
      $lt->persetasi_bobot = $persetasi_bobot; // Persentasi Bobot didapat achievemenst * bobots
      $lt->log_date = now();
      $lt->save();

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
