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

    // Menampilkan Dashboard Real
    public function dashboard($value='')
    {
       return view('dashboard');
    }

    // Menampilkan Dashboard Justifikasi
    public function dashboard_verifikasi($value='')
    {
      return view('dashboard-verifikasi');
    }

    // Menampilkan Dashboard Justifikasi
    public function performance_system_verifikasi($value='')
    {
      return view('performance-system-verifikasi');
    }

    // Func Admin Start
    public function dashboard_admin()
    {
        $list_layanan = DB::table('layanans')->where([
            ['is_enabled', '=', '1']
        ])->get();
        return view('admin.dashboard',['list_layanan' => $list_layanan]);
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
        $list_layanan = DB::table('layanans')->where([
            ['is_enabled', '=', '1']
        ])->get();
        return view('admin.report',['list_layanan' => $list_layanan]);

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

      if ($request->is_justifikasi != 1) {
        DB::table('daily_transaksis')->updateOrInsert(
            [
              'id_layanan' => $saveResult->id_layanan
            , 'id_parameter' => $saveResult->id_parameter
            , 'id_formulasi' => $saveResult->id_formulasi
            , 'tanggal' => $saveResult->tanggal
            ],
            [
              'nilai' => $saveResult->nilai
              , 'user_input' => $saveResult->user_input
            ]
        );
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
          #$qWhere .= '(ifnull(AVG(CASE WHEN id_formulasi=16 THEN nilai ELSE NULL END), 0) +  ifnull(AVG(CASE WHEN id_formulasi=17 THEN nilai ELSE NULL END), 0) +  ifnull(AVG(CASE WHEN id_formulasi=18 THEN nilai ELSE NULL END), 0) +  ifnull(AVG(CASE WHEN id_formulasi=19 THEN nilai ELSE NULL END), 0) +  ifnull(AVG(CASE WHEN id_formulasi=20 THEN nilai ELSE NULL END), 0))/5';
        $qWhere .= '(ifnull(AVG(CASE WHEN id_formulasi=16 THEN nilai ELSE NULL END), 0) +  ifnull(AVG(CASE WHEN id_formulasi=17 THEN nilai ELSE NULL END), 0) +  ifnull(AVG(CASE WHEN id_formulasi=18 THEN nilai ELSE NULL END), 0) +  ifnull(AVG(CASE WHEN id_formulasi=19 THEN nilai ELSE NULL END), 0) +  ifnull(AVG(CASE WHEN id_formulasi=20 THEN nilai ELSE NULL END), 0))/(SELECT COUNT(id) AS pembagi FROM formulasis WHERE id_parameter = 6 AND is_enabled = "1" )';
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
          $qWhere .= 'nilai';
          $param_compare = '>=';
          break;
        case 18:
          $qWhere .= 'SUM(nilai)';
          $param_compare = '>=';
          break;

        default:
          abort(500, 'Error, Parameter not found');
          break;
      }
      $qWhere .= ", 0) as realisasi";
      $dt->selectRaw($qWhere);
      $dt->whereRaw('MONTH(daily_transaksis_justifikasi.tanggal) = MONTH("'.$request->input_date.'") AND YEAR(daily_transaksis_justifikasi.tanggal) = YEAR("'.$request->input_date.'")');
      if ($id_parameter == 15 OR $id_parameter == 16 OR $id_parameter == 17) {
          $dt->orderBy('daily_transaksis_justifikasi.tanggal', 'desc');
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

      if ($request->is_justifikasi != 1) {
        DB::table('log_transaksis')->insert(
            [
              'layanan' => $lt->layanan
              , 'parameter' => $lt->parameter
              , 'satuan' => $lt->satuan
              , 'target' => $lt->target
              , 'bobot' => $lt->bobot
              , 'realisasi' => $lt->realisasi
              , 'achievement' => $lt->achievement
              , 'persetasi_bobot' => $lt->persetasi_bobot
              , 'perfomance' => $lt->perfomance
              , 'log_date' => $lt->log_date
              , 'created_at' => $lt->created_at
              , 'updated_at' => $lt->updated_at
            ]
        );
      }

      if ($request->is_justifikasi == 1 && $request->has('note_anomaly')) {
        DB::table('anomaly_note')->updateOrInsert(
            [
              'id_layanan' => auth()->user()->layanan
              , 'id_parameter' => $request->select_parameter
              , 'id_formulasi' => $request->select_formulasi
              , 'tanggal' => $input_date
            ],
            [
              'note' => $request->note_anomaly
              , 'user_input' => auth()->user()->username
              , 'created_at' => now()
              , 'updated_at' => now()
            ]
        );
      }

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
      $table_name = 'log_transaksis';
      $table_name1 = 'daily_transaksis';
      if ($request->headers->has('dashboard-type') && $request->header('dashboard-type', 'default_value') == 1) {
        $table_name = 'log_transaksis_justifikasi';
        $table_name1 = 'daily_transaksis_justifikasi';
      }

      $list_date = DB::table($table_name1)->where([
        [''.$table_name1.'.id_parameter', '=', $request->id_parameter]
      ])
      ->whereRaw('MONTH('.$table_name1.'.tanggal) = '.$request->month.' AND YEAR('.$table_name1.'.tanggal) = '.$request->year)
      ->selectRaw('
        DAY('.$table_name1.'.tanggal) AS day
        , '.$table_name1.'.tanggal AS date
      ')
      ->orderBy('day')
      ->distinct()->get();
      $datas = [];
      foreach ($list_date as $date_value) {
        $dataToJoin = DB::table($table_name1)
        ->where([
          [''.$table_name1.'.id_layanan', '=', auth()->user()->layanan]
          , [''.$table_name1.'.id_parameter', '=', $request->id_parameter]
        ])
        //->whereRaw('MONTH('.$table_name1.'.tanggal) = MONTH(CURRENT_DATE()) AND YEAR('.$table_name1.'.tanggal) = YEAR(CURRENT_DATE())')
        //->whereRaw('MONTH('.$table_name1.'.tanggal) = '.$request->month.' AND YEAR('.$table_name1.'.tanggal) = '.$request->year)
        ->whereRaw(''.$table_name1.'.tanggal = "'.$date_value->date.'"')
        ->selectRaw(
          'DAY('.$table_name1.'.tanggal) AS DAY
          , DATE_FORMAT('.$table_name1.'.tanggal, "%Y-%m") AS DATE
          , '.$table_name1.'.nilai AS value_item
          , '.$table_name1.'.id_formulasi AS id_formulasi'
        );

        $data = DB::table('formulasis')
        ->leftJoin('parameters', 'parameters.id', '=', 'formulasis.id_parameter')
        ->leftJoinSub($dataToJoin, 'res', function ($join) {
            $join->on('res.id_formulasi', '=', 'formulasis.id');
        })
        ->where([
          ['parameters.id_layanan', '=', auth()->user()->layanan]
          , ['parameters.id', '=', $request->id_parameter]
          , ['formulasis.is_enabled', '=', '1']
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
        $total_data_daily = DB::table($table_name1)
        ->where([
          [''.$table_name1.'.id_layanan', '=', auth()->user()->layanan]
          , [''.$table_name1.'.id_parameter', '=', $request->id_parameter]
        ])
        //->whereRaw('MONTH('.$table_name1.'.tanggal) = MONTH(CURRENT_DATE()) AND YEAR('.$table_name1.'.tanggal) = YEAR(CURRENT_DATE())')
        //->whereRaw('MONTH('.$table_name1.'.tanggal) = '.$request->month.' AND YEAR('.$table_name1.'.tanggal) = '.$request->year)
        ->whereRaw(''.$table_name1.'.tanggal = "'.$date_value->date.'"')
        ->selectRaw(
          'IFNULL('.$table_name1.'.tanggal, DAY("'.$date_value->date.'"))  AS day
          , IFNULL('.$table_name1.'.tanggal, "'.$date_value->date.'") AS date
          , IFNULL(SUM('.$table_name1.'.nilai), "") AS total_item'
        )->groupBy(''.$table_name1.'.tanggal')->first();
        */

        // Realisasi daily
        /*$dt = daily_transaksi::where([
          ['daily_transaksis.id_layanan', '=', auth()->user()->layanan]
          , ['daily_transaksis.id_parameter', '=', $request->id_parameter]
        ]);*/
        $dt = DB::table($table_name1)->where([
          [''.$table_name1.'.id_layanan', '=', auth()->user()->layanan]
          , [''.$table_name1.'.id_parameter', '=', $request->id_parameter]
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
            #$qWhere .= '(ifnull(AVG(CASE WHEN id_formulasi=16 THEN nilai ELSE NULL END), 0) +  ifnull(AVG(CASE WHEN id_formulasi=17 THEN nilai ELSE NULL END), 0) +  ifnull(AVG(CASE WHEN id_formulasi=18 THEN nilai ELSE NULL END), 0) +  ifnull(AVG(CASE WHEN id_formulasi=19 THEN nilai ELSE NULL END), 0) +  ifnull(AVG(CASE WHEN id_formulasi=20 THEN nilai ELSE NULL END), 0))/5';
            $qWhere .= '(ifnull(AVG(CASE WHEN id_formulasi=16 THEN nilai ELSE NULL END), 0) +  ifnull(AVG(CASE WHEN id_formulasi=17 THEN nilai ELSE NULL END), 0) +  ifnull(AVG(CASE WHEN id_formulasi=18 THEN nilai ELSE NULL END), 0) +  ifnull(AVG(CASE WHEN id_formulasi=19 THEN nilai ELSE NULL END), 0) +  ifnull(AVG(CASE WHEN id_formulasi=20 THEN nilai ELSE NULL END), 0))/(SELECT COUNT(id) AS pembagi FROM formulasis WHERE id_parameter = 6 AND is_enabled = "1" )';
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
            $qWhere .= 'SUM(nilai)'; // ini belum bener itungannya
            $param_compare = '>=';
            break;

          default:
          abort(500, 'Error, Parameter not found');
          break;
        }
        $qWhere .= ", 0) as realisasi";
        $dt->selectRaw($qWhere);
        $dt->whereRaw(''.$table_name1.'.tanggal = "'.$date_value->date.'"');
        if ($request->id_parameter == 15 OR $request->id_parameter == 16 OR $request->id_parameter == 17) {
            $dt->orderBy(''.$table_name1.'.tanggal', 'desc');
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
      if (auth()->user()->layanan == 0) {
          return $this->get_perfomance_comparation_admin($request);
      }
      $table_name = 'log_transaksis';
      if ($request->headers->has('dashboard-type') && $request->header('dashboard-type', 'default_value') == 1) {
        $table_name = 'log_transaksis_justifikasi';
      }
      $list_parameter = DB::table('parameters')->where([
        ['id_layanan', '=', auth()->user()->layanan]
        , ['is_enabled', '=', '1']
      ])->get();
      $total_perfomance = [];
      $req_date = date("Y-m-d" ,strtotime($request->year."-".$request->month."-1"));
      //$qWhere = ['MONTH('.$table_name.'.log_date) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR('.$table_name.'.log_date) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)', 'MONTH('.$table_name.'.log_date) = MONTH(CURRENT_DATE()) AND YEAR('.$table_name.'.log_date) = YEAR(CURRENT_DATE())'];
      $qWhere = ['MONTH('.$table_name.'.log_date) = MONTH("'.$req_date.'" - INTERVAL 1 MONTH) AND YEAR('.$table_name.'.log_date) = YEAR("'.$req_date.'" - INTERVAL 1 MONTH)', 'MONTH('.$table_name.'.log_date) = MONTH("'.$req_date.'") AND YEAR('.$table_name.'.log_date) = YEAR("'.$req_date.'")'];
      $name_desc = ['Last Month', 'This Month'];
      for ($i=0; $i < count($qWhere) ; $i++) {
        $tempArr = [];
        $tempVal = 0;
        foreach ($list_parameter as $key) {
          $tempArr[] = DB::table($table_name)->where([
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

    public function get_perfomance_comparation_admin(Request $request)
    {
      $list_parameter = DB::table('parameters')->where([
        ['id_layanan', '=', $request->layanan],
        ['is_enabled', '=', '1']
      ])->get();
      $total_perfomance = [];
      $req_date = date("Y-m-d" ,strtotime($request->year."-".$request->month."-1"));
      //$qWhere = ['MONTH(log_transaksis.log_date) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(log_transaksis.log_date) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)', 'MONTH(log_transaksis.log_date) = MONTH(CURRENT_DATE()) AND YEAR(log_transaksis.log_date) = YEAR(CURRENT_DATE())'];
      // $qWhere = ['MONTH(log_transaksis.log_date) = MONTH("'.$req_date.'" - INTERVAL 1 MONTH) AND YEAR(log_transaksis.log_date) = YEAR("'.$req_date.'" - INTERVAL 1 MONTH)', 'MONTH(log_transaksis.log_date) = MONTH("'.$req_date.'") AND YEAR(log_transaksis.log_date) = YEAR("'.$req_date.'")'];
      // $name_desc = ['Last Month', 'This Month'];
      for ($i=1; $i < 13 ; $i++) {
        $tempArr = [];
        $tempVal = 0;
        foreach ($list_parameter as $key) {
          $tempArr[] = DB::table('log_transaksis')->where([
            ['layanan', '=', $key->id_layanan]
            , ['parameter', '=', $key->id]
          ])
          ->whereRaw('MONTH(log_transaksis.log_date) = '.$i.' AND YEAR(log_transaksis.log_date) = YEAR("'.$req_date.'")')
          ->orderBy('created_at', 'desc')->first();
        }
        for ($j=0; $j < count($tempArr); $j++) {
          $tempVal += optional($tempArr[$j])->perfomance;
        }
        $monthNum  = $i;
        $monthName = date('F', mktime(0, 0, 0, $monthNum, 10));
        $total_perfomance[] = (object) ["desc"=>$monthName, "total_perfomance"=>$tempVal];
      }

      return response()->json($total_perfomance);
    }

    # Get Realiasi Monthly
    public function get_realisasi_monthly(Request $request)
    {
      $table_name = 'log_transaksis';
      if ($request->headers->has('dashboard-type') && $request->header('dashboard-type', 'default_value') == 1) {
        $table_name = 'log_transaksis_justifikasi';
      }
      $list_parameter = DB::table('parameters')->where([
        ['id_layanan', '=', auth()->user()->layanan]
        , ['is_enabled', '=', '1']
      ])->get();
      $list_realisasi = [];
      //$qWhere = ['MONTH('.$table_name.'.log_date) = MONTH(CURRENT_DATE()) AND YEAR('.$table_name.'.log_date) = YEAR(CURRENT_DATE())'];
      $qWhere = ['MONTH('.$table_name.'.log_date) = "'.$request->month.'" AND YEAR('.$table_name.'.log_date) = "'.$request->year.'"'];
      $name_desc = ['This Month'];
      for ($i=0; $i < count($qWhere) ; $i++) {
        $tempArr = [];
        $tempVal = 0;
        foreach ($list_parameter as $key) {
          $tempArr[] = DB::table($table_name)->where([
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
      $table_name = 'log_transaksis';
      if ($request->headers->has('dashboard-type') && $request->header('dashboard-type', 'default_value') == 1) {
        $table_name = 'log_transaksis_justifikasi';
      }
      // Buat Variable yang nampung kpi bobot dan target per paramater
      $kpi = DB::table('kpi')->where([
        //['id_layanan', '=', 1]
        //['id_layanan', '=', auth()->user()->layanan],
        ['active', '=', 'current']
      ]);
      if (auth()->user()->layanan == 0) {
          if ( request()->ajax() ) {
            if (!empty($request->layanan)) {
              $kpi->where([
                ['id_layanan', '=', $request->layanan]
              ]);
            }
            else{
                abort(500, "No request value for layanan.");
            }
          }
      }
      else{
        $kpi->where([
            ['id_layanan', '=', auth()->user()->layanan]
          ]);
      }

      $parameter = DB::table('parameters')
      ->leftJoinSub($kpi, 'kpi', function ($join) {
        $join->on('kpi.id_parameter', '=', 'parameters.id');
      })
      ->selectRaw('
        parameters.`id`
        , parameters.`parameter_desc`
        , kpi.`satuan` as satuan
        , IFNULL(CONCAT(ROUND(kpi.`target`), kpi.`satuan`), "Undefined") AS target
        , IFNULL(CONCAT(kpi.`bobot`, "%"), "Undefined") AS bobot
        , IFNULL(ROUND(kpi.`target`), 0) AS target_number
        , IFNULL(ROUND(kpi.`bobot`), 0) AS bobot_number
        ')
      ->where([
        ['parameters.is_enabled', '=', '1']
        //, ['parameters.id_layanan', '=', 1]
        //, ['parameters.id_layanan', '=', auth()->user()->layanan]
      ]);
      if (auth()->user()->layanan == 0) {
          if ( request()->ajax() ) {
            if (!empty($request->layanan)) {
              $parameter->where([
                ['parameters.id_layanan', '=', $request->layanan]
              ]);
            }
            else{
                abort(500, "No request value for layanan.");
            }
          }
      }
      else{
        $parameter->where([
            ['parameters.id_layanan', '=', auth()->user()->layanan]
          ]);
      }

      $parameter = $parameter->orderBy('parameters.id')->get();

      // Variable Temp buat nampiungs
      $tempArr = [];

      foreach ($parameter as $key) {
        $lt = DB::table($table_name)->where([
            ['parameter', '=', $key->id]
            //, ['layanan', '=', auth()->user()->layanan]
        ]);
        if (auth()->user()->layanan == 0) {
              if ( request()->ajax() ) {
                if (!empty($request->layanan)) {
                  $lt->where([
                    ['layanan', '=', $request->layanan]
                  ]);
                }
                else{
                    abort(500, "No request value for layanan.");
                }
              }
          }
          else{
            $lt->where([
                ['layanan', '=', auth()->user()->layanan]
              ]);
          }
        //->whereRaw('MONTH(log_date) = MONTH(CURDATE()) AND YEAR(log_date) = YEAR(CURDATE())')
        $lt = $lt->whereRaw('MONTH(log_date) = '.$request->month.' AND YEAR(log_date) = '.$request->year.'')
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
          if ($key->id == 6) {
              $progress_bar_color = ($realisasi < $target ? 'success' : 'danger');
              if ($realisasi == 0) {
                $progress_bar_width = 0;
              }
              else{
                $progress_bar_width = (($target/60)/($realisasi/60)*100);
            }
          }
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
          , "satuan" => optional($key)->satuan
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
      $table_name = 'log_transaksis';
      $table_name1 = 'daily_transaksis';
      if ($request->headers->has('dashboard-type') && $request->header('dashboard-type', 'default_value') == 1) {
        $table_name = 'log_transaksis_justifikasi';
        $table_name1 = 'daily_transaksis_justifikasi';
      }
      // Fetch Parameter berdasarkan Layanan
      $list_parameter = DB::table('parameters')->where([
        //['id_layanan', '=', auth()->user()->layanan],
        ['is_enabled', '=', '1']
      ]);
      if (auth()->user()->layanan == 0) {
          if ( request()->ajax() ) {
            if (!empty($request->layanan)) {
              $list_parameter->where([
                ['id_layanan', '=', $request->layanan]
              ]);
            }
            else{
                abort(500, "No request value for layanan.");
            }
          }
      }
      else{
        $list_parameter->where([
            ['id_layanan', '=', auth()->user()->layanan]
          ]);
      }
      $list_parameter = $list_parameter->get();
      $tempArr = [];
      foreach ($list_parameter as $parameter) {
        // Fetch Nilai Summary Bulanan dari Log Transaksi
        $summary_per_parameter = DB::table($table_name)->where([
          //['layanan', '=', auth()->user()->layanan],
          ['parameter', '=', $parameter->id]
        ]);
        if (auth()->user()->layanan == 0) {
              if ( request()->ajax() ) {
                if (!empty($request->layanan)) {
                  $summary_per_parameter->where([
                    ['layanan', '=', $request->layanan]
                  ]);
                }
                else{
                    abort(500, "No request value for layanan.");
                }
              }
          }
          else{
            $summary_per_parameter->where([
                ['layanan', '=', auth()->user()->layanan]
              ]);
          }
        //->whereRaw('MONTH(log_date) = MONTH(CURDATE()) AND YEAR(log_date) = YEAR(CURDATE())')
        $summary_per_parameter = $summary_per_parameter->whereRaw('MONTH(log_date) = '.$request->month.' AND YEAR(log_date) = '.$request->year.'')
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
          $target = optional($summary_per_parameter)->target;
        }
        else{
          $realisasi = 0;
          $achievement = 0;
          $persetasi_bobot = 0;
          $perfomance = 0;
          $satuan = '%';
          $target = 0;
        }


        $tempArr[] = [
          "paramater_id" => $parameter->id
          , "parameter_desc" => $parameter->parameter_desc
          , "realisasi" => $realisasi
          , "achievement" => $achievement
          , "persetasi_bobot" => $persetasi_bobot
          , "perfomance" => $perfomance
          , "satuan" => $satuan
          , "target" => $target
        ];
      }

      // Looping per tempArr untuk dapat hasil counting formulasi per parameter
      for ($i=0; $i < count($tempArr) ; $i++) {
        // Total Input Daily per Formulasi berdasarkan Parameter
        # Kalo parameternya Download Apps dan Rating Playstore maka total input hitungannya berikut
        if ($tempArr[$i]['paramater_id'] == 15 OR $tempArr[$i]['paramater_id'] == 16 OR $tempArr[$i]['paramater_id'] == 17) {
            $total_input = DB::table($table_name1)->selectRaw('
              id_formulasi
              , nilai AS total
              ')
            //->whereRaw('MONTH(tanggal) = MONTH(CURDATE()) AND YEAR(tanggal) = YEAR(CURDATE()) AND id_layanan = 1')
            ->whereRaw('MONTH(tanggal) = '.$request->month.' AND YEAR(tanggal) = '.$request->year.' AND id_layanan = '.auth()->user()->layanan.' AND id_parameter = '.$tempArr[$i]['paramater_id'])
            ->orderBy('tanggal', 'desc')->limit(1);
        }
        elseif ( $tempArr[$i]['paramater_id'] == 6 OR $tempArr[$i]['paramater_id'] == 12 ) {
            $total_input = DB::table($table_name1)->selectRaw('
              id_formulasi
              , AVG(nilai) AS total
              ')
            //->whereRaw('MONTH(tanggal) = MONTH(CURDATE()) AND YEAR(tanggal) = YEAR(CURDATE()) AND id_layanan = 1')
            ->whereRaw('MONTH(tanggal) = '.$request->month.' AND YEAR(tanggal) = '.$request->year.' AND id_layanan = '.auth()->user()->layanan)
            ->groupBy('id_formulasi');
        }
        else{
            $total_input = DB::table($table_name1)->selectRaw('
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
            , "formulasi_total" => ($total_per_formulasi->total == null ? 0 : $total_per_formulasi->total )
          ];
        }
        $tempArr[$i]['realisasi_per_formulasi'] = $tempArr1;
      }


      return response()->json($tempArr);
    }

    # Get List Notes Anomali
    public function get_list_anomaly(Request $request)
    {
      $datas = DB::table('anomaly_note')->join('formulasis', 'anomaly_note.id_formulasi', '=', 'formulasis.id')
        ->where([
          ['formulasis.is_enabled', '=', '1']
          , ['anomaly_note.id_parameter', '=', $request->id_parameter]
        ])
        ->whereYear('anomaly_note.tanggal', $request->year)
        ->whereMonth('anomaly_note.tanggal', $request->month)
        ->orderBy('anomaly_note.tanggal')
        ->select('formulasis.formulasi_desc', 'anomaly_note.tanggal', 'anomaly_note.note', 'anomaly_note.user_input')
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
      if (auth()->user()->layanan == 0) {
          $datas = DB::table('daily_transaksis');
      }
      else{
          $datas = DB::table('daily_transaksis')->where([
            ['id_layanan','=',auth()->user()->layanan]
          ]);
      }
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
