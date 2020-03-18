<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Koneksi via DB
use DB;
use App\daily_transaksi;
use App\log_transaksi;
use App\kpi;

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
          $qWhere .= 'SUM(CASE WHEN id_formulasi = 14 THEN nilai ELSE 0 END)/SUM(nilai)*100'; // Average beluus
          $param_compare = '';
          break;
        case 7:
          $qWhere .= 'SUM(CASE WHEN id_formulasi = 22 THEN nilai ELSE 0 END)/SUM(nilai)*100';
          $param_compare = '';
          break;
        case 8:
          $qWhere .= 'SUM(CASE WHEN id_formulasi = 27 THEN nilai ELSE 0 END)/SUM(CASE WHEN id_formulasi = 26 THEN nilai ELSE 0 END)*100';
          $param_compare = '';
          break;
        case 9:
          $qWhere .= 'SUM(CASE WHEN id_formulasi = 29 THEN nilai ELSE 0 END)/SUM(nilai)*100';
          $param_compare = '';
          break;
        case 10:
          $qWhere .= 'SUM(CASE WHEN id_formulasi = 32 THEN nilai ELSE 0 END)/SUM(nilai)*100';
          $param_compare = '';
          break;
        // C4
        case 11:
          $qWhere .= 'SUM(CASE WHEN id_formulasi = 35 THEN nilai ELSE 0 END)/SUM(CASE WHEN id_formulasi = 34 THEN nilai ELSE 0 END)*100';
          $param_compare = '';
          break;
        case 12:
          $qWhere .= 'SUM(nilai)*100';
          $param_compare = '';
          break;
        case 13:
          $qWhere .= 'SUM(CASE WHEN id_formulasi = 38 THEN nilai ELSE 0 END)/SUM(CASE WHEN id_formulasi = 37 THEN nilai ELSE 0 END)*100';
          $param_compare = '';
          break;
        case 14:
          $qWhere .= 'SUM(CASE WHEN id_formulasi = 40 THEN nilai ELSE 0 END)/SUM(CASE WHEN id_formulasi = 39 THEN nilai ELSE 0 END)*100';
          $param_compare = '';
          break;
        // MyIndihome
        case 15:
          $qWhere .= 'SUM(nilai)*100';
          $param_compare = '';
          break;
        case 16:
          $qWhere .= 'SUM(nilai)*100';
          $param_compare = '';
          break;
        case 17:
          $qWhere .= 'SUM(nilai)*100';
          $param_compare = '';
          break;
        case 18:
          $qWhere .= 'SUM(nilai)*100';
          $param_compare = '';
          break;

        default:
          abort(500, 'Error, Parameter not found');
          break;
      }
      $qWhere .= ", 0) as realisasi";
      $dt->selectRaw($qWhere);
      $dt = $dt->whereRaw('MONTH(daily_transaksis.tanggal) = MONTH("'.$request->input_date.'") AND YEAR(daily_transaksis.tanggal) = YEAR("'.$request->input_date.'")')->first();
      $realisasi = $dt->realisasi;
      $achievement = ($realisasi/$kpi_model->target)*100;
      // Ada yang berbeda perhiyungannya dari yg umum, dilakuka disini aja perubahannya
      // Contoh Untuk Parameter Quality Layanan (ID Parameter 5) dari Layanan 147 (ID Layanan 1)
      switch ($id_parameter) {
        case 5:
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
          , IFNULL(res.value_item, "") AS value_item
        ')
        ->orderBy('parameters.id')
        ->orderBy('res.id_formulasi')
        ->orderBy('res.day')
        ->get();
        
        foreach ($data as $detailed_data) {
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
      $qWhere = ['MONTH(log_transaksis.log_date) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(log_transaksis.log_date) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)', 'MONTH(log_transaksis.log_date) = MONTH(CURRENT_DATE()) AND YEAR(log_transaksis.log_date) = YEAR(CURRENT_DATE())'];
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
          ];
        }
        $list_realisasi = (object) $list_realisasi;
      }

      return response()->json($list_realisasi);
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
}
