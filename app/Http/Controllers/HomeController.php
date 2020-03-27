<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Koneksi via DB
use DB;
use App\daily_transaksi;
use App\log_transaksi;
use App\kpi;

// handle Exception Error
use Exception;

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
      switch (auth()->user()->layanan) {
        case 0:
          return $this->dashboard_admin();
          break;
        case 1:
          return $this->dashboard();
          break;
        case 2:
          return $this->dashboard();
          break;
        case 3:
          return $this->dashboard();
          break;
        case 4:
          return $this->dashboard();
          break;

        default:
          abort(404);
          break;
      }
    }
    public function dashboard($value='')
    {
       return view('dashboard');
    }
    public function dashboard_147()
    {
        $service_level = DB::table('log_transaksis')->where([
            ['layanan', '=', '1']
            , ['parameter', '=', '1']
        ])
        ->whereRaw('MONTH(log_date) = MONTH(CURDATE()) AND YEAR(log_date) = YEAR(CURDATE())')
        ->orderBy('created_at', 'desc')->first();

        $fcr = DB::table('log_transaksis')->where([
            ['layanan', '=', '1']
            , ['parameter', '=', '2']
            //, ['log_date', '=', date('Y-m-d')]
        ])
        ->whereRaw('MONTH(log_date) = MONTH(CURDATE()) AND YEAR(log_date) = YEAR(CURDATE())')
        ->orderBy('created_at', 'desc')->first();

        $rasio_sales = DB::table('log_transaksis')->where([
            ['layanan', '=', '1']
            , ['parameter', '=', '3']
        ])
        ->whereRaw('MONTH(log_date) = MONTH(CURDATE()) AND YEAR(log_date) = YEAR(CURDATE())')
        ->orderBy('created_at', 'desc')->first();

        $ces = DB::table('log_transaksis')->where([
            ['layanan', '=', '1']
            , ['parameter', '=', '4']
        ])
        ->whereRaw('MONTH(log_date) = MONTH(CURDATE()) AND YEAR(log_date) = YEAR(CURDATE())')
        ->orderBy('created_at', 'desc')->first();

        $quality_layanan = DB::table('log_transaksis')->where([
            ['layanan', '=', '1']
            , ['parameter', '=', '5']
        ])
        ->whereRaw('MONTH(log_date) = MONTH(CURDATE()) AND YEAR(log_date) = YEAR(CURDATE())')
        ->orderBy('created_at', 'desc')->first();

        // Bobot dan Target Resources
        $kpi = DB::table('kpi')->where([
          ['id_layanan', '=', 1]
          , ['active', '=', 'current']
        ]);
        $parameter = DB::table('parameters')
        ->leftJoinSub($kpi, 'kpi', function ($join) {
            $join->on('kpi.id_parameter', '=', 'parameters.id');
        })
        ->selectRaw('
          parameters.`id`
          , parameters.`parameter_desc`
          , IFNULL(CONCAT(ROUND(kpi.`target`), kpi.`satuan`), "Undefined") AS target
          , IFNULL(CONCAT(kpi.`bobot`, "%"), "Undefined") AS bobot
          ')
        ->where([
          ['parameters.is_enabled', '=', '1']
          , ['parameters.id_layanan', '=', 1]
        ])
        ->orderBy('parameters.id')->get();

        // Total Input Daily per Formulasi berdasarkan Parameter
        $total_input = DB::table('daily_transaksis')->selectRaw('
          id_formulasi
          , SUM(nilai) AS total
          ')->whereRaw('MONTH(tanggal) = MONTH(CURDATE()) AND YEAR(tanggal) = YEAR(CURDATE()) AND id_layanan = 1')
        ->groupBy('id_formulasi');
        $total_input_per_formulasi = DB::table('formulasis')
        ->selectRaw('
          formulasis.id_parameter,
          formulasis.id,
          formulasis.formulasi_desc,
          res.total
          ')
        ->leftJoinSub($total_input, 'res', function ($join) {
              $join->on('res.id_formulasi', '=', 'formulasis.id');
          })
        ->whereRaw("
          formulasis.is_enabled = '1' AND formulasis.id_parameter IN (SELECT id FROM parameters WHERE id_layanan = 1 AND is_enabled = '1')
          ")
        ->get();



        $t_bobot = optional($service_level)->persetasi_bobot+optional($fcr)->persetasi_bobot+optional($rasio_sales)->persetasi_bobot+optional($ces)->persetasi_bobot+optional($quality_layanan)->persetasi_bobot;

        $percent = 100;
        $ttl_service_level_target_bobot = optional($service_level)->target + optional($service_level)->bobot;
        if($ttl_service_level_target_bobot != 0 && $ttl_service_level_target_bobot > 0){
            //$target_service_level = (optional($service_level)->target/$ttl_service_level_target_bobot)*$percent;
            $target_service_level = (optional($service_level)->realisasi);
            $bobot_service_level = $percent-$target_service_level;
            $width_progressbar_sl = round(($target_service_level/$service_level->target)*$percent);
        }else{
            $target_service_level= 0;
            $bobot_service_level= 0;
            $width_progressbar_sl = 0;
        }

        $ttl_fcr_target_bobot = optional($fcr)->target + optional($fcr)->bobot;
        if($ttl_fcr_target_bobot != 0 && $ttl_fcr_target_bobot > 0){
            //$target_fcr = (optional($fcr)->target*$percent)/$ttl_fcr_target_bobot;
            $target_fcr = (optional($fcr)->realisasi);
            $bobot_fcr = $percent-$target_fcr;
            $width_progressbar_fcr = round(($target_fcr/$fcr->target)*$percent);
        }else{
            $target_fcr= 0;
            $bobot_fcr= 0;
            $width_progressbar_fcr = 0;
        }

        $ttl_rasio_sales_target_bobot = optional($rasio_sales)->target + optional($rasio_sales)->bobot;
        if($ttl_rasio_sales_target_bobot != 0 && $ttl_rasio_sales_target_bobot > 0){
            //$target_rasio_sales = (optional($rasio_sales)->target*$percent)/$ttl_rasio_sales_target_bobot;
            $target_rasio_sales = (optional($rasio_sales)->realisasi);
            $bobot_rasio_sales = $percent-$target_rasio_sales;
            $width_progressbar_rs = round(($target_rasio_sales/$rasio_sales->target)*$percent);
        }else{
            $target_rasio_sales= 0;
            $bobot_rasio_sales= 0;
            $width_progressbar_rs = 0;
        }

        $ttl_ces_target_bobot = optional($ces)->target + optional($ces)->bobot;
        if($ttl_ces_target_bobot != 0 && $ttl_ces_target_bobot > 0){
            //$target_ces = (optional($ces)->target*$percent)/$ttl_ces_target_bobot;
            $target_ces = (optional($ces)->realisasi);
            $bobot_ces = $percent-$target_ces;
            $width_progressbar_ces = round(($target_ces/$ces->target)*$percent);
        }else{
            $target_ces= 0;
            $bobot_ces= 0;
            $width_progressbar_ces = 0;
        }

        $ttl_quality_layanan_target_bobot = optional($quality_layanan)->target + optional($quality_layanan)->bobot;
        if($ttl_quality_layanan_target_bobot != 0 && $ttl_quality_layanan_target_bobot > 0){
            //$target_quality_layanan = (optional($quality_layanan)->target*$percent)/$ttl_quality_layanan_target_bobot;
            $target_quality_layanan = (optional($quality_layanan)->realisasi);
            $bobot_quality_layanan = $percent-$target_quality_layanan;
            $width_progressbar_ql = round(($target_quality_layanan/$quality_layanan->target)*$percent);
            $revert_target = 100 - $quality_layanan->target;
        }else{
            $target_quality_layanan= 0;
            $bobot_quality_layanan= 0;
            $width_progressbar_ql = 0;
            $revert_target = 0;
        }

        return view('dashboard',[
            'service_level' => $service_level,
            'fcr' => $fcr,
            'rasio_sales' => $rasio_sales,
            'ces' => $ces,
            'quality_layanan' => $quality_layanan,
            't_bobot' => $t_bobot,

            'target_service_level' => $target_service_level,
            'width_progressbar_sl' => $width_progressbar_sl,
            'width_progressbar_fcr' => $width_progressbar_fcr,
            'width_progressbar_rs' => $width_progressbar_rs,
            'width_progressbar_ces' => $width_progressbar_ces,
            'width_progressbar_ql' => $width_progressbar_ql,
            'bobot_service_level' => $bobot_service_level,
            'revert_target' => $revert_target,
            'target_fcr' => $target_fcr,
            'bobot_fcr' => $bobot_fcr,
            'target_rasio_sales' => $target_rasio_sales,
            'bobot_rasio_sales' => $bobot_rasio_sales,
            'target_ces' => $target_ces,
            'bobot_ces' => $bobot_ces,
            'target_quality_layanan' => $target_quality_layanan,
            'bobot_quality_layanan' => $bobot_quality_layanan,

            'kpiObject' => $parameter,
            'input_per_formulasi' => $total_input_per_formulasi,
        ]);
    }
    public function dashboard_digital_media()
    {
        $service_level = DB::table('log_transaksis')->where([
            ['layanan', '=', '2']
            , ['parameter', '=', '6']
        ])
        ->whereRaw('MONTH(log_date) = MONTH(CURDATE()) AND YEAR(log_date) = YEAR(CURDATE())')
        ->orderBy('created_at', 'desc')->first();

        $fcr = DB::table('log_transaksis')->where([
            ['layanan', '=', '2']
            , ['parameter', '=', '7']
            //, ['log_date', '=', date('Y-m-d')]
        ])
        ->whereRaw('MONTH(log_date) = MONTH(CURDATE()) AND YEAR(log_date) = YEAR(CURDATE())')
        ->orderBy('created_at', 'desc')->first();

        $rasio_sales = DB::table('log_transaksis')->where([
            ['layanan', '=', '2']
            , ['parameter', '=', '8']
        ])
        ->whereRaw('MONTH(log_date) = MONTH(CURDATE()) AND YEAR(log_date) = YEAR(CURDATE())')
        ->orderBy('created_at', 'desc')->first();

        $ces = DB::table('log_transaksis')->where([
            ['layanan', '=', '2']
            , ['parameter', '=', '9']
        ])
        ->whereRaw('MONTH(log_date) = MONTH(CURDATE()) AND YEAR(log_date) = YEAR(CURDATE())')
        ->orderBy('created_at', 'desc')->first();

        $quality_layanan = DB::table('log_transaksis')->where([
            ['layanan', '=', '2']
            , ['parameter', '=', '10']
        ])
        ->whereRaw('MONTH(log_date) = MONTH(CURDATE()) AND YEAR(log_date) = YEAR(CURDATE())')
        ->orderBy('created_at', 'desc')->first();

        // Bobot dan Target Resources
        $kpi = DB::table('kpi')->where([
          ['id_layanan', '=', 2]
          , ['active', '=', 'current']
        ]);
        $parameter = DB::table('parameters')
        ->leftJoinSub($kpi, 'kpi', function ($join) {
            $join->on('kpi.id_parameter', '=', 'parameters.id');
        })
        ->selectRaw('
          parameters.`id`
          , parameters.`parameter_desc`
          , IFNULL(CONCAT(ROUND(kpi.`target`), kpi.`satuan`), "Undefined") AS target
          , IFNULL(CONCAT(kpi.`bobot`, "%"), "Undefined") AS bobot
          ')
        ->where([
          ['parameters.is_enabled', '=', '1']
          , ['parameters.id_layanan', '=', 2]
        ])
        ->orderBy('parameters.id')->get();

        // Total Input Daily per Formulasi berdasarkan Parameter
        $total_input = DB::table('daily_transaksis')->selectRaw('
          id_formulasi
          , SUM(nilai) AS total
          ')->whereRaw('MONTH(tanggal) = MONTH(CURDATE()) AND YEAR(tanggal) = YEAR(CURDATE()) AND id_layanan = 2')
        ->groupBy('id_formulasi');
        $total_input_per_formulasi = DB::table('formulasis')
        ->selectRaw('
          formulasis.id_parameter,
          formulasis.id,
          formulasis.formulasi_desc,
          res.total
          ')
        ->leftJoinSub($total_input, 'res', function ($join) {
              $join->on('res.id_formulasi', '=', 'formulasis.id');
          })
        ->whereRaw("
          formulasis.is_enabled = '1' AND formulasis.id_parameter IN (SELECT id FROM parameters WHERE id_layanan = 2 AND is_enabled = '1')
          ")
        ->get();



        $t_bobot = optional($service_level)->persetasi_bobot+optional($fcr)->persetasi_bobot+optional($rasio_sales)->persetasi_bobot+optional($ces)->persetasi_bobot+optional($quality_layanan)->persetasi_bobot;

        $percent = 100;
        $ttl_service_level_target_bobot = optional($service_level)->target + optional($service_level)->bobot;
        if($ttl_service_level_target_bobot != 0 && $ttl_service_level_target_bobot > 0){
            //$target_service_level = (optional($service_level)->target/$ttl_service_level_target_bobot)*$percent;
            $target_service_level = (optional($service_level)->realisasi);
            $bobot_service_level = $percent-$target_service_level;
            $width_progressbar_sl = round(($target_service_level/$service_level->target)*$percent);
        }else{
            $target_service_level= 0;
            $bobot_service_level= 0;
            $width_progressbar_sl = 0;
        }

        $ttl_fcr_target_bobot = optional($fcr)->target + optional($fcr)->bobot;
        if($ttl_fcr_target_bobot != 0 && $ttl_fcr_target_bobot > 0){
            //$target_fcr = (optional($fcr)->target*$percent)/$ttl_fcr_target_bobot;
            $target_fcr = (optional($fcr)->realisasi);
            $bobot_fcr = $percent-$target_fcr;
            $width_progressbar_fcr = round(($target_fcr/$fcr->target)*$percent);
        }else{
            $target_fcr= 0;
            $bobot_fcr= 0;
            $width_progressbar_fcr = 0;
        }

        $ttl_rasio_sales_target_bobot = optional($rasio_sales)->target + optional($rasio_sales)->bobot;
        if($ttl_rasio_sales_target_bobot != 0 && $ttl_rasio_sales_target_bobot > 0){
            //$target_rasio_sales = (optional($rasio_sales)->target*$percent)/$ttl_rasio_sales_target_bobot;
            $target_rasio_sales = (optional($rasio_sales)->realisasi);
            $bobot_rasio_sales = $percent-$target_rasio_sales;
            $width_progressbar_rs = round(($target_rasio_sales/$rasio_sales->target)*$percent);
        }else{
            $target_rasio_sales= 0;
            $bobot_rasio_sales= 0;
            $width_progressbar_rs = 0;
        }

        $ttl_ces_target_bobot = optional($ces)->target + optional($ces)->bobot;
        if($ttl_ces_target_bobot != 0 && $ttl_ces_target_bobot > 0){
            //$target_ces = (optional($ces)->target*$percent)/$ttl_ces_target_bobot;
            $target_ces = (optional($ces)->realisasi);
            $bobot_ces = $percent-$target_ces;
            $width_progressbar_ces = round(($target_ces/$ces->target)*$percent);
        }else{
            $target_ces= 0;
            $bobot_ces= 0;
            $width_progressbar_ces = 0;
        }

        $ttl_quality_layanan_target_bobot = optional($quality_layanan)->target + optional($quality_layanan)->bobot;
        if($ttl_quality_layanan_target_bobot != 0 && $ttl_quality_layanan_target_bobot > 0){
            //$target_quality_layanan = (optional($quality_layanan)->target*$percent)/$ttl_quality_layanan_target_bobot;
            $target_quality_layanan = (optional($quality_layanan)->realisasi);
            $bobot_quality_layanan = $percent-$target_quality_layanan;
            $width_progressbar_ql = round(($target_quality_layanan/$quality_layanan->target)*$percent);
            $revert_target = 100 - $quality_layanan->target;
        }else{
            $target_quality_layanan= 0;
            $bobot_quality_layanan= 0;
            $width_progressbar_ql = 0;
            $revert_target = 0;
        }

        return view('dashboard',[
            'service_level' => $service_level,
            'fcr' => $fcr,
            'rasio_sales' => $rasio_sales,
            'ces' => $ces,
            'quality_layanan' => $quality_layanan,
            't_bobot' => $t_bobot,

            'target_service_level' => $target_service_level,
            'width_progressbar_sl' => $width_progressbar_sl,
            'width_progressbar_fcr' => $width_progressbar_fcr,
            'width_progressbar_rs' => $width_progressbar_rs,
            'width_progressbar_ces' => $width_progressbar_ces,
            'width_progressbar_ql' => $width_progressbar_ql,
            'bobot_service_level' => $bobot_service_level,
            'revert_target' => $revert_target,
            'target_fcr' => $target_fcr,
            'bobot_fcr' => $bobot_fcr,
            'target_rasio_sales' => $target_rasio_sales,
            'bobot_rasio_sales' => $bobot_rasio_sales,
            'target_ces' => $target_ces,
            'bobot_ces' => $bobot_ces,
            'target_quality_layanan' => $target_quality_layanan,
            'bobot_quality_layanan' => $bobot_quality_layanan,

            'kpiObject' => $parameter,
            'input_per_formulasi' => $total_input_per_formulasi,
        ]);
    }

    // Func Admin Start
    public function dashboard_admin()
    {
        return view('admin.dashboard');
    }
    public function daily_admin()
    {
        if ( auth()->user()->layanan != 0 ) {
            abort(401);
        }
        return view('admin.daily');
    }
    public function report(Request $request)
    {
        if ( auth()->user()->layanan != 0 ) {
            abort(401);
        }
        return view('admin.report');
    }
    // Func Admin End

    // Func-func page daily input
    public function cc_147()
    {
      if ( auth()->user()->layanan != 1 ) {
        abort(401);
      }
      return $this->daily_page();
    }
    public function digital_media()
    {
      if ( auth()->user()->layanan != 2 ) {
        abort(401);
      }
      return $this->daily_page();
    }
    public function c4()
    {
      if ( auth()->user()->layanan != 3 ) {
        abort(401);
      }
      return $this->daily_page();
    }
    public function myindihome()
    {
      if ( auth()->user()->layanan != 4 ) {
        abort(401);
      }
      return $this->daily_page();
    }
    # Redirect ke Daily
    public function daily_page()
    {
      $data['parameters_tab'] = DB::table('parameters')->select('id', 'parameter_desc')->where([
        ['is_enabled','=','1']
      ])->where('id_layanan', '=', auth()->user()->layanan)->get();

      return view('cc-147')->with('data',$data);;
    }

    # Save Target, Bobot dan Satuan
    public function save_daily_input(Request $request)
    {
      $todayDate = date('Y-m-d');
      # Error messages validation
      $messages = [
        'select_parameter.required' => "You haven't choose parameter yet.",
        'select_formulasi.required'  => "You haven't choose item yet.",
        'input_date.required'  => "You haven't input a date yet.",
        'input_date.before_or_equal'  => "You input a date greater than today date.",
      ];
      # Rules Validation
      $validation = $this->validate($request, [
        'select_parameter' => 'required',
        'select_formulasi' => 'required',
        'input_date' => 'required|before_or_equal:'.$todayDate,
      ], $messages);

      $id_layanan = auth()->user()->layanan;
      $id_parameter = $request->select_parameter;
      $id_formulasi = $request->select_formulasi;
      $input_date = $request->input_date;

      $saveResult = daily_transaksi::updateOrCreate(
        [
          'id_layanan' => auth()->user()->layanan
          , 'id_parameter' => $request->select_parameter
          , 'id_formulasi' => $request->select_formulasi
          , 'tanggal' => $input_date//now()->subDays(1)->format('Y-m-d')
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
      $qWhere = "IFNULL(";
      switch ($id_parameter) {
        // 147
        case 1:
          $qWhere .= 'SUM(CASE WHEN id_formulasi = 2 THEN nilai ELSE 0 END)/SUM(CASE WHEN id_formulasi = 1 THEN nilai ELSE 0 END)*100';
          $param_compare = '>=';
          break;
        case 2:
          $qWhere .= 'SUM(CASE WHEN id_formulasi = 4 THEN nilai ELSE 0 END)/SUM(nilai)*100';
          $param_compare = '>=';
          break;
        case 3:
          $qWhere .= 'SUM(CASE WHEN id_formulasi = 8 OR id_formulasi = 7 THEN nilai ELSE 0 END)/SUM(CASE WHEN id_formulasi = 9 THEN nilai ELSE 0 END)*100';
          $param_compare = '>=';
          break;
        case 4:
          $qWhere .= 'SUM(CASE WHEN id_formulasi = 11 THEN nilai ELSE 0 END)/SUM(nilai)*100';
          $param_compare = '>=';
          break;
        case 5:
          $qWhere .= 'SUM(CASE WHEN id_formulasi = 15 THEN nilai ELSE 0 END)/SUM(nilai)*100';
          $param_compare = '<=';
          break;
        // Digital Media
        case 6:
          $qWhere .= '(AVG(CASE WHEN id_formulasi=16 THEN nilai ELSE NULL END) + AVG(CASE WHEN id_formulasi=17 THEN nilai ELSE NULL END) + AVG(CASE WHEN id_formulasi=18 THEN nilai ELSE NULL END) + AVG(CASE WHEN id_formulasi=19 THEN nilai ELSE NULL END) + AVG(CASE WHEN id_formulasi=20 THEN nilai ELSE NULL END))/5';
          $param_compare = '<=';
          break;
        case 7:
          $qWhere .= 'SUM(CASE WHEN id_formulasi = 22 THEN nilai ELSE 0 END)/SUM(nilai)*100';
          $param_compare = '>=';
          break;
        case 8:
          $qWhere .= 'SUM(CASE WHEN id_formulasi = 25 OR id_formulasi = 26 THEN nilai ELSE 0 END)/SUM(CASE WHEN id_formulasi = 27 THEN nilai ELSE 0 END)*100';
          $param_compare = '>=';
          break;
        case 9:
          $qWhere .= 'SUM(CASE WHEN id_formulasi = 29 THEN nilai ELSE 0 END)/SUM(nilai)*100';
          $param_compare = '>=';
          break;
        case 10:
          $qWhere .= 'SUM(CASE WHEN id_formulasi = 33 THEN nilai ELSE 0 END)/SUM(nilai)*100';
          $param_compare = '<=';
          break;
        // C4
        case 11:
          $qWhere .= 'SUM(CASE WHEN id_formulasi = 35 THEN nilai ELSE 0 END)/SUM(CASE WHEN id_formulasi = 34 THEN nilai ELSE 0 END)*100';
          $param_compare = '>=';
          break;
        case 12:
          $qWhere .= 'AVG(nilai)';
          $param_compare = '>=';
          break;
        case 13:
          $qWhere .= 'SUM(CASE WHEN id_formulasi = 38 THEN nilai ELSE 0 END)/SUM(CASE WHEN id_formulasi = 37 THEN nilai ELSE 0 END)*100';
          $param_compare = '>=';
          break;
        case 14:
          $qWhere .= 'SUM(CASE WHEN id_formulasi = 40 THEN nilai ELSE 0 END)/SUM(CASE WHEN id_formulasi = 39 THEN nilai ELSE 0 END)*100';
          $param_compare = '>=';
          break;
        // MyIndihome
        case 15:
          $qWhere .= 'nilai';
          $param_compare = '>=';
          break;
        case 16:
          $qWhere .= 'nilai';
          $param_compare = '>=';
          break;
        case 17:
          $qWhere .= 'nilai'; // ini belum bener itungannya
          $param_compare = '>=';
          break;
        case 18:
          $qWhere .= 'SUM(nilai)*100'; // ini belum bener itungannya
          $param_compare = '>=';
          break;

        default:
          abort(500, 'Error, Parameter not found');
          break;
      }
      $qWhere .= ", 0) as realisasi";
      $dt->selectRaw($qWhere);
      $dt->whereRaw('MONTH(daily_transaksis.tanggal) = MONTH("'.$request->input_date.'") AND YEAR(daily_transaksis.tanggal) = YEAR("'.$request->input_date.'")');
      if ($id_parameter == 15 OR $id_parameter == 16 OR $id_parameter == 17) {
          $dt->orderBy('daily_transaksis.tanggal', 'desc');
      }
      $dt = $dt->first();
      $realisasi = $dt->realisasi;
      $achievement = ($realisasi/$kpi_model->target)*100;
      // Ada yang berbeda perhiyungannya dari yg umum, dilakuka disini aja perubahannya
      // Contoh Untuk Parameter Quality Layanan (ID Parameter 5) dari Layanan 147 (ID Layanan 1)
      switch ($id_parameter) {
        case 5:
          $achievement = (100-$realisasi)/(100-$kpi_model->target)*100;
          break;
        case 6:
          if ($realisasi == 0) {
            $achievement = 0;
          }
          else{
            $achievement = ($kpi_model->target/60)/($realisasi/60)*100;
          }
          break;
        case 10:
          $achievement = (100-$realisasi)/(100-$kpi_model->target)*100;
          break;
        default:
          # do nothing
          break;
      }

      $perfomance = ($achievement*$kpi_model->bobot)/100; // berubah nama jadi perfomance
      // Buat nentuin nilai persentasi bobot
      if ( $this->compare_two_value($realisasi, $kpi_model->target, $param_compare) ) {
        $persetasi_bobot = $kpi_model->bobot;
      }
      else{
        $persetasi_bobot = ($kpi_model->bobot*$achievement)/100;
      }

      $lt->realisasi = $realisasi; // Realisasi nyari dari data daily input per month
      $lt->achievement = $achievement; // Nilai Acgievments didapat dari Realiasi / Target
      $lt->perfomance = $perfomance; // perfomance  didapat achievemenst * bobots
      $lt->persetasi_bobot = $persetasi_bobot;
      $lt->log_date = $input_date;//now()->subDays(1);
      $lt->save();

      // Return hasilnya
      return response()->json(['success' => "success"], 200);
    }

    # Func untuk comparison
    protected function compare_two_value($val1, $val2, $operator)
    {
      switch ($operator) {
        case '==':
          return $val1 == $val2;
          break;
        case '<':
          return $val1 < $val2;
          break;
        case '>':
          return $val1 > $val2;
          break;
        case '!=':
          return $val1 != $val2;
          break;
        case '>=':
          return $val1 >= $val2;
          break;
        case '<=':
          return $val1 <= $val2;
          break;

        default:
          return null;
          break;
      }
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
      $list_date = DB::table('daily_transaksis')->where([
        ['daily_transaksis.id_parameter', '=', $request->id_parameter]
      ])
      ->whereRaw('MONTH(daily_transaksis.tanggal) = '.$request->month.' AND YEAR(daily_transaksis.tanggal) = '.$request->year)
      ->selectRaw('
        DAY(daily_transaksis.tanggal) AS day
        , daily_transaksis.tanggal AS date
      ')
      ->orderBy('day')
      ->distinct()->get();
      $datas = [];
      foreach ($list_date as $date_value) {
        $dataToJoin = DB::table('daily_transaksis')
        ->where([
          ['daily_transaksis.id_layanan', '=', auth()->user()->layanan]
          , ['daily_transaksis.id_parameter', '=', $request->id_parameter]
        ])
        //->whereRaw('MONTH(daily_transaksis.tanggal) = MONTH(CURRENT_DATE()) AND YEAR(daily_transaksis.tanggal) = YEAR(CURRENT_DATE())')
        //->whereRaw('MONTH(daily_transaksis.tanggal) = '.$request->month.' AND YEAR(daily_transaksis.tanggal) = '.$request->year)
        ->whereRaw('daily_transaksis.tanggal = "'.$date_value->date.'"')
        ->selectRaw(
          'DAY(daily_transaksis.tanggal) AS DAY
          , DATE_FORMAT(daily_transaksis.tanggal, "%Y-%m") AS DATE
          , daily_transaksis.nilai AS value_item
          , daily_transaksis.id_formulasi AS id_formulasi'
        );

        $data = DB::table('formulasis')
        ->leftJoin('parameters', 'parameters.id', '=', 'formulasis.id_parameter')
        ->leftJoinSub($dataToJoin, 'res', function ($join) {
            $join->on('res.id_formulasi', '=', 'formulasis.id');
        })
        ->where([
          ['parameters.id_layanan', '=', auth()->user()->layanan]
          , ['parameters.id', '=', $request->id_parameter]
        ])
        ->selectRaw('
          parameters.parameter_desc AS parameter_desc
          , formulasis.formulasi_desc AS value_desc
          , IFNULL(res.day, DAY("'.$date_value->date.'"))  AS `day`
          , IFNULL(res.date, "'.$date_value->date.'") AS `date`
          , IFNULL(res.value_item, 0) AS value_item
        ')
        ->orderBy('parameters.id')
        ->orderBy('res.id_formulasi')
        ->orderBy('res.day')
        ->get();

        /*
        // Penambahan buat ngambil nilai total per harinya
        $total_data_daily = DB::table('daily_transaksis')
        ->where([
          ['daily_transaksis.id_layanan', '=', auth()->user()->layanan]
          , ['daily_transaksis.id_parameter', '=', $request->id_parameter]
        ])
        //->whereRaw('MONTH(daily_transaksis.tanggal) = MONTH(CURRENT_DATE()) AND YEAR(daily_transaksis.tanggal) = YEAR(CURRENT_DATE())')
        //->whereRaw('MONTH(daily_transaksis.tanggal) = '.$request->month.' AND YEAR(daily_transaksis.tanggal) = '.$request->year)
        ->whereRaw('daily_transaksis.tanggal = "'.$date_value->date.'"')
        ->selectRaw(
          'IFNULL(daily_transaksis.tanggal, DAY("'.$date_value->date.'"))  AS day
          , IFNULL(daily_transaksis.tanggal, "'.$date_value->date.'") AS date
          , IFNULL(SUM(daily_transaksis.nilai), "") AS total_item'
        )->groupBy('daily_transaksis.tanggal')->first();
        */

        // Realisasi daily
        $dt = daily_transaksi::where([
          ['daily_transaksis.id_layanan', '=', auth()->user()->layanan]
          , ['daily_transaksis.id_parameter', '=', $request->id_parameter]
        ]);
        $qWhere = "IFNULL(";
        switch ($request->id_parameter) {
          // 147
          case 1:
            $qWhere .= 'SUM(CASE WHEN id_formulasi = 2 THEN nilai ELSE 0 END)/SUM(CASE WHEN id_formulasi = 1 THEN nilai ELSE 0 END)*100';
            $param_compare = '>=';
            break;
          case 2:
            $qWhere .= 'SUM(CASE WHEN id_formulasi = 4 THEN nilai ELSE 0 END)/SUM(nilai)*100';
            $param_compare = '>=';
            break;
          case 3:
            $qWhere .= 'SUM(CASE WHEN id_formulasi = 8 OR id_formulasi = 7 THEN nilai ELSE 0 END)/SUM(CASE WHEN id_formulasi = 9 THEN nilai ELSE 0 END)*100';
            $param_compare = '>=';
            break;
          case 4:
            $qWhere .= 'SUM(CASE WHEN id_formulasi = 11 THEN nilai ELSE 0 END)/SUM(nilai)*100';
            $param_compare = '>=';
            break;
          case 5:
            $qWhere .= 'SUM(CASE WHEN id_formulasi = 14 THEN nilai ELSE 0 END)/SUM(nilai)*100'; // khusus untuk di yg di chart, di tampulkannya jumlah OK/Total
            $param_compare = '<=';
            break;
          // Digital Media
          case 6:
            $qWhere .= '(AVG(CASE WHEN id_formulasi=16 THEN nilai ELSE NULL END) + AVG(CASE WHEN id_formulasi=17 THEN nilai ELSE NULL END) + AVG(CASE WHEN id_formulasi=18 THEN nilai ELSE NULL END) + AVG(CASE WHEN id_formulasi=19 THEN nilai ELSE NULL END) + AVG(CASE WHEN id_formulasi=20 THEN nilai ELSE NULL END))/5'; // Average beluus
            $param_compare = '<=';
            break;
          case 7:
            $qWhere .= 'SUM(CASE WHEN id_formulasi = 22 THEN nilai ELSE 0 END)/SUM(nilai)*100';
            $param_compare = '>=';
            break;
          case 8:
            $qWhere .= 'SUM(CASE WHEN id_formulasi = 25 OR id_formulasi = 26 THEN nilai ELSE 0 END)/SUM(CASE WHEN id_formulasi = 27 THEN nilai ELSE 0 END)*100';
            $param_compare = '>=';
            break;
          case 9:
            $qWhere .= 'SUM(CASE WHEN id_formulasi = 29 THEN nilai ELSE 0 END)/SUM(nilai)*100';
            $param_compare = '>=';
            break;
          case 10:
            $qWhere .= 'SUM(CASE WHEN id_formulasi = 32 THEN nilai ELSE 0 END)/SUM(nilai)*100';
            $param_compare = '<=';
            break;
          // C4
          case 11:
            $qWhere .= 'SUM(CASE WHEN id_formulasi = 35 THEN nilai ELSE 0 END)/SUM(CASE WHEN id_formulasi = 34 THEN nilai ELSE 0 END)*100';
            $param_compare = '>=';
            break;
          case 12:
            $qWhere .= 'AVG(nilai)';
            $param_compare = '>=';
            break;
          case 13:
            $qWhere .= 'SUM(CASE WHEN id_formulasi = 38 THEN nilai ELSE 0 END)/SUM(CASE WHEN id_formulasi = 37 THEN nilai ELSE 0 END)*100';
            $param_compare = '>=';
            break;
          case 14:
            $qWhere .= 'SUM(CASE WHEN id_formulasi = 40 THEN nilai ELSE 0 END)/SUM(CASE WHEN id_formulasi = 39 THEN nilai ELSE 0 END)*100';
            $param_compare = '>=';
            break;
          // MyIndihome
          case 15:
            $qWhere .= 'nilai';
            $param_compare = '>=';
            break;
          case 16:
            $qWhere .= 'nilai';
            $param_compare = '>=';
            break;
          case 17:
            $qWhere .= 'nilai'; // ini belum bener itungannya
            $param_compare = '>=';
            break;
          case 18:
            $qWhere .= 'SUM(nilai)*100'; // ini belum bener itungannya
            $param_compare = '>=';
            break;

          default:
          abort(500, 'Error, Parameter not found');
          break;
        }
        $qWhere .= ", 0) as realisasi";
        $dt->selectRaw($qWhere);
        $dt->whereRaw('daily_transaksis.tanggal = "'.$date_value->date.'"');
        if ($request->id_parameter == 15 OR $request->id_parameter == 16 OR $request->id_parameter == 17) {
            $dt->orderBy('daily_transaksis.tanggal', 'desc');
        }
        $dt = $dt->first();
        $realisasi = number_format((float)$dt->realisasi, 2, '.', '');

        foreach ($data as $detailed_data) {
          //$detailed_data->total_item = $total_data_daily->total_item;
          $detailed_data->realisasi = $realisasi;
          $datas[] = $detailed_data;
        }
      }


      return response()->json( $datas );
    }

    # Perfomance Comparation Monthly
    public function get_perfomance_comparation(Request $request)
    {
      $list_parameter = DB::table('parameters')->where([
        ['id_layanan', '=', auth()->user()->layanan]
        , ['is_enabled', '=', '1']
      ])->get();
      $total_perfomance = [];
      $req_date = date("Y-m-d" ,strtotime($request->year."-".$request->month."-1"));
      //$qWhere = ['MONTH(log_transaksis.log_date) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(log_transaksis.log_date) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)', 'MONTH(log_transaksis.log_date) = MONTH(CURRENT_DATE()) AND YEAR(log_transaksis.log_date) = YEAR(CURRENT_DATE())'];
      $qWhere = ['MONTH(log_transaksis.log_date) = MONTH("'.$req_date.'" - INTERVAL 1 MONTH) AND YEAR(log_transaksis.log_date) = YEAR("'.$req_date.'" - INTERVAL 1 MONTH)', 'MONTH(log_transaksis.log_date) = MONTH("'.$req_date.'") AND YEAR(log_transaksis.log_date) = YEAR("'.$req_date.'")'];
      $name_desc = ['Last Month', 'This Month'];
      for ($i=0; $i < count($qWhere) ; $i++) {
        $tempArr = [];
        $tempVal = 0;
        foreach ($list_parameter as $key) {
          $tempArr[] = DB::table('log_transaksis')->where([
            ['layanan', '=', $key->id_layanan]
            , ['parameter', '=', $key->id]
          ])
          ->whereRaw($qWhere[$i])
          ->orderBy('created_at', 'desc')->first();
        }
        for ($j=0; $j < count($tempArr); $j++) {
          $tempVal += optional($tempArr[$j])->perfomance;
        }
        $total_perfomance[] = (object) ["desc"=>$name_desc[$i], "total_perfomance"=>$tempVal];
      }

      return response()->json($total_perfomance);
    }

    # Get Realiasi Monthly
    public function get_realisasi_monthly(Request $request)
    {
      $list_parameter = DB::table('parameters')->where([
        ['id_layanan', '=', auth()->user()->layanan]
        , ['is_enabled', '=', '1']
      ])->get();
      $list_realisasi = [];
      //$qWhere = ['MONTH(log_transaksis.log_date) = MONTH(CURRENT_DATE()) AND YEAR(log_transaksis.log_date) = YEAR(CURRENT_DATE())'];
      $qWhere = ['MONTH(log_transaksis.log_date) = "'.$request->month.'" AND YEAR(log_transaksis.log_date) = "'.$request->year.'"'];
      $name_desc = ['This Month'];
      for ($i=0; $i < count($qWhere) ; $i++) {
        $tempArr = [];
        $tempVal = 0;
        foreach ($list_parameter as $key) {
          $tempArr[] = DB::table('log_transaksis')->where([
            ['layanan', '=', $key->id_layanan]
            , ['parameter', '=', $key->id]
          ])
          ->whereRaw($qWhere[$i])
          ->orderBy('created_at', 'desc')->first();
        }
        for ($j=0; $j < count($tempArr); $j++) {
          $list_realisasi[] = [
            "realisasi"=>optional($tempArr[$j])->realisasi
            , "satuan"=>optional($tempArr[$j])->satuan
          ];
        }
        $list_realisasi = (object) $list_realisasi;
      }

      return response()->json($list_realisasi);
    }

    # Get KPI Information dan Progress Monthly
    public function get_kpi_information_progress(Request $request)
    {
      // Buat Variable yang nampung kpi bobot dan target per paramater
      $kpi = DB::table('kpi')->where([
        //['id_layanan', '=', 1]
        ['id_layanan', '=', auth()->user()->layanan]
        , ['active', '=', 'current']
      ]);
      $parameter = DB::table('parameters')
      ->leftJoinSub($kpi, 'kpi', function ($join) {
        $join->on('kpi.id_parameter', '=', 'parameters.id');
      })
      ->selectRaw('
        parameters.`id`
        , parameters.`parameter_desc`
        , IFNULL(CONCAT(ROUND(kpi.`target`), kpi.`satuan`), "Undefined") AS target
        , IFNULL(CONCAT(kpi.`bobot`, "%"), "Undefined") AS bobot
        , IFNULL(ROUND(kpi.`target`), 0) AS target_number
        , IFNULL(ROUND(kpi.`bobot`), 0) AS bobot_number
        ')
      ->where([
        ['parameters.is_enabled', '=', '1']
        //, ['parameters.id_layanan', '=', 1]
        , ['parameters.id_layanan', '=', auth()->user()->layanan]
      ])
      ->orderBy('parameters.id')->get();

      // Variable Temp buat nampiungs
      $tempArr = [];

      foreach ($parameter as $key) {
        $lt = DB::table('log_transaksis')->where([
            ['layanan', '=', auth()->user()->layanan]
            , ['parameter', '=', $key->id]
        ])
        //->whereRaw('MONTH(log_date) = MONTH(CURDATE()) AND YEAR(log_date) = YEAR(CURDATE())')
        ->whereRaw('MONTH(log_date) = '.$request->month.' AND YEAR(log_date) = '.$request->year.'')
        ->orderBy('created_at', 'desc')->first();
        if ($lt) {
          $realisasi = optional($lt)->realisasi;
          $achievement = optional($lt)->achievement;
          $persetasi_bobot = optional($lt)->persetasi_bobot;
          $perfomance = optional($lt)->perfomance;
          $satuan = optional($lt)->satuan;
        }
        else{
          $realisasi = 0;
          $achievement = 0;
          $persetasi_bobot = 0;
          $perfomance = 0;
          $satuan = '%';
        }
        if ($key) {
          $target = optional($key)->target_number;
          $bobot = optional($key)->bobot_number;
        }
        else{
          $target = 0;
          $bobot = 0;
        }
        $progress_bar = '<li class="mt-4 mb-4">';
        if ($key->id == 5 OR $key->id == 10) {
          $progress_bar .= '<div class="d-flex align-items-center mb-1">'.$key->parameter_desc.' <span class="text-muted ml-auto">Target NOK < '.$key->target.' | Bobot '.$key->bobot.'</span></div>';
          $progress_bar .= '<div class="progress" style="height: 1.5rem;">';
          $progress_bar_color = ($realisasi < $target ? 'success' : 'danger');
          $progress_bar_width = round($realisasi);
          //$progress_bar_width = 0;
          if ($progress_bar_width == 0) {
            $progress_bar_width1 = 0;
          }
          else{
            $progress_bar_width1 = 100 - $progress_bar_width;
          }
          $progress_bar .= '<div class="progress-bar progress-bar-striped progress-bar-animated bg-info" style="width: '.$progress_bar_width1.'%">';
          $progress_bar .= '<span>'.$progress_bar_width1.'% OK</span></div>';
          $progress_bar .= '<div class="progress-bar progress-bar-striped progress-bar-animated bg-'.$progress_bar_color.'" style="width: '.$progress_bar_width.'%">';
          $progress_bar .= '<span>'.round($realisasi).''.$satuan.' NOK</span></div></div></li>';
        }
        else{
          $progress_bar .= '<div class="d-flex align-items-center mb-1">'.$key->parameter_desc.' <span class="text-muted ml-auto">Target '.$key->target.' | Bobot '.$key->bobot.'</span></div>';
          $progress_bar .= '<div class="progress" style="height: 1.5rem;">';
          $progress_bar_color = ($realisasi > $target ? 'success' : 'danger');
          $progress_bar_width = round(($realisasi/$target)*100);
          //$progress_bar_width = 0;
          $progress_bar .= '<div class="progress-bar progress-bar-striped progress-bar-animated bg-'.$progress_bar_color.'" style="width: '.$progress_bar_width.'%">';
          $progress_bar .= '<span>'.round($realisasi);
          if ($key->id != 15 AND $key->id != 16) {
            $progress_bar .= $satuan;
          }
          $progress_bar .= ' Complete</span></div></div></li>';
        }

        $tempArr[] = [
          "parameter_id" => $key->id
          , "parameter_desc" => $key->parameter_desc
          , "target" => optional($key)->target
          , "bobot" => optional($key)->bobot
          , "realisasi" => $realisasi
          //, "achievement" => $achievement
          //, "persetasi_bobot" => $persetasi_bobot
          //, "perfomance" => $perfomance
          , "progress_bar_element" => $progress_bar
        ];
      }

      return response()->json( $tempArr);
    }

    public function get_summary_layanan(Request $request)
    {
      // Fetch Parameter berdasarkan Layanan
      $list_parameter = DB::table('parameters')->where([
        ['id_layanan', '=', auth()->user()->layanan]
        , ['is_enabled', '=', '1']
      ])->get();
      $tempArr = [];
      foreach ($list_parameter as $parameter) {
        // Fetch Nilai Summary Bulanan dari Log Transaksi
        $summary_per_parameter = DB::table('log_transaksis')->where([
          ['layanan', '=', auth()->user()->layanan]
          , ['parameter', '=', $parameter->id]
        ])
        //->whereRaw('MONTH(log_date) = MONTH(CURDATE()) AND YEAR(log_date) = YEAR(CURDATE())')
        ->whereRaw('MONTH(log_date) = '.$request->month.' AND YEAR(log_date) = '.$request->year.'')
        ->orderBy('created_at', 'desc')->first();
        if ($summary_per_parameter) {
          $realisasi = round(optional($summary_per_parameter)->realisasi);
          if ($parameter->id == 6) { // kalo parameternya bebrnilai service level dari layanan digital media, buat realisasi nya ga di roound
                $realisasi = optional($summary_per_parameter)->realisasi;
          }
          $achievement = optional($summary_per_parameter)->achievement;
          $persetasi_bobot = optional($summary_per_parameter)->persetasi_bobot;
          $perfomance = optional($summary_per_parameter)->perfomance;
          $satuan = optional($summary_per_parameter)->satuan;
        }
        else{
          $realisasi = 0;
          $achievement = 0;
          $persetasi_bobot = 0;
          $perfomance = 0;
          $satuan = '%';
        }


        $tempArr[] = [
          "paramater_id" => $parameter->id
          , "parameter_desc" => $parameter->parameter_desc
          , "realisasi" => $realisasi
          , "achievement" => $achievement
          , "persetasi_bobot" => $persetasi_bobot
          , "perfomance" => $perfomance
          , "satuan" => $satuan
        ];
      }

      // Looping per tempArr untuk dapat hasil counting formulasi per parameter
      for ($i=0; $i < count($tempArr) ; $i++) {
        // Total Input Daily per Formulasi berdasarkan Parameter
        # Kalo parameternya Download Apps dan Rating Playstore maka total input hitungannya berikut
        if ($tempArr[$i]['paramater_id'] == 15 OR $tempArr[$i]['paramater_id'] == 16 OR $tempArr[$i]['paramater_id'] == 17) {
            $total_input = DB::table('daily_transaksis')->selectRaw('
              id_formulasi
              , nilai AS total
              ')
            //->whereRaw('MONTH(tanggal) = MONTH(CURDATE()) AND YEAR(tanggal) = YEAR(CURDATE()) AND id_layanan = 1')
            ->whereRaw('MONTH(tanggal) = '.$request->month.' AND YEAR(tanggal) = '.$request->year.' AND id_layanan = '.auth()->user()->layanan.' AND id_parameter = '.$tempArr[$i]['paramater_id'])
            ->orderBy('tanggal', 'desc')->limit(1);
        }
        elseif ( $tempArr[$i]['paramater_id'] == 6 OR $tempArr[$i]['paramater_id'] == 12 ) {
            $total_input = DB::table('daily_transaksis')->selectRaw('
              id_formulasi
              , AVG(nilai) AS total
              ')
            //->whereRaw('MONTH(tanggal) = MONTH(CURDATE()) AND YEAR(tanggal) = YEAR(CURDATE()) AND id_layanan = 1')
            ->whereRaw('MONTH(tanggal) = '.$request->month.' AND YEAR(tanggal) = '.$request->year.' AND id_layanan = '.auth()->user()->layanan)
            ->groupBy('id_formulasi');
        }
        else{
            $total_input = DB::table('daily_transaksis')->selectRaw('
              id_formulasi
              , SUM(nilai) AS total
              ')
            //->whereRaw('MONTH(tanggal) = MONTH(CURDATE()) AND YEAR(tanggal) = YEAR(CURDATE()) AND id_layanan = 1')
            ->whereRaw('MONTH(tanggal) = '.$request->month.' AND YEAR(tanggal) = '.$request->year.' AND id_layanan = '.auth()->user()->layanan)
            ->groupBy('id_formulasi');
        }

        $total_input_per_formulasi = DB::table('formulasis')
        ->selectRaw('
          formulasis.id_parameter,
          formulasis.id,
          formulasis.formulasi_desc,
          res.total
          ')
        ->leftJoinSub($total_input, 'res', function ($join) {
          $join->on('res.id_formulasi', '=', 'formulasis.id');
        })
        /*->whereRaw("
          formulasis.is_enabled = '1' AND formulasis.id_parameter IN (SELECT id FROM parameters WHERE id_layanan = 1 AND is_enabled = '1')
          ")*/
        ->whereRaw("
          formulasis.is_enabled = '1' AND formulasis.id_parameter = ".$tempArr[$i]['paramater_id']."
          ")
        ->get();
        $tempArr1 = [];
        foreach ($total_input_per_formulasi as $total_per_formulasi) {
          $tempArr1[] = [
            "formulasi_id" => $total_per_formulasi->id
            , "formulasi_desc" => $total_per_formulasi->formulasi_desc
            , "formulasi_total" => $total_per_formulasi->total
          ];
        }
        $tempArr[$i]['realisasi_per_formulasi'] = $tempArr1;
      }


      return response()->json($tempArr);
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
          $datas->whereYear('tanggal', '=', $request->select_year)->select(DB::raw('MONTH(tanggal) as month'))->distinct()->orderBy('month', 'asc');
        }
        else {
          $datas->select(DB::raw('YEAR(tanggal) as year'))->distinct()->orderBy('year', 'desc');
        }
      }
      $datas = $datas->get();
      return response()->json($datas);
    }

    // Tes PHPOffice/PHPWord
    public function generateDocx()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        $section = $phpWord->addSection();

        $description = "SiMance Report by Docx.";

        //$section->addImage("http://itsolutionstuff.com/frontTheme/images/logo.png");
        $section->addText($description);


        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        try {
            $objWriter->save(storage_path('helloWorld.docx'));
        } catch (Exception $e) {
        }


        return response()->download(storage_path('helloWorld.docx'));
    }
}
