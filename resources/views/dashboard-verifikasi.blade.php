@extends('layouts/app')

@section('title', 'Dashboard')

@section('liblary')
<script src="assets/js/plugins/visualization/echarts/echarts.min.js"></script>
<script src="assets/js/plugins/forms/styling/uniform.min.js"></script>
<script src="assets/js/plugins/forms/selects/select2.min.js"></script>
<script src="assets/js/plugins/extensions/jquery_ui/interactions.min.js"></script>
@endsection

@section('extra-liblary')
{{--
<script src="assets/js/demo_pages/charts/echarts/columns_waterfalls.js"></script>
<script src="assets/js/demo_pages/charts/echarts/lines.js"></script>
--}}
<script src="assets/js/demo_pages/form_select2.js"></script>
<script src="assets/js/demo_pages/form_layouts.js"></script>
@endsection

@section('script')
<script type="text/javascript">
  //Inisiasi BlockUI
  $('.card-body').block({ 
      message: '<i class="icon-spinner4 spinner"></i>',
      //timeout: 2000, //unblock after 2 seconds
      overlayCSS: {
          backgroundColor: '#fff',
          //opacity: 0.8,
          opacity: 0.95,
          cursor: 'wait'
      },
      css: {
          border: 0,
          padding: 0,
          backgroundColor: 'transparent'
      }
  });

  function trans_val(data, key) {
    var resArr = [];
    data.filter(function(item){
      var i = resArr.findIndex(x => (x[key] == item[key]));
      if(i <= -1){
            resArr.push(item);
      }
      return null;
    });
    //console.log(resArr)
    var retArr = []
    for (var i = 0; i < resArr.length; i++) {
      retArr.push(resArr[i][key])
    }
    return retArr;
  }
  function titleCase(string) {
    var sentence = string.toLowerCase().split(" ");
    for(var i = 0; i< sentence.length; i++){
      sentence[i] = sentence[i][0].toUpperCase() + sentence[i].slice(1);
    }
    document.write(sentence.join(" "));
    return sentence;
   }


  // Func
  function get_kpi_information_progress(year_value, month_value) {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        , 'dashboard-type': 1
      }
    });
    $.ajax({
     type:"post",
     url:'/get-kpi-information-progress',
         data: {year: year_value, month: month_value},
         success: function(data){
          var progress_bar = '';
          for (var i = 0; i < data.length; i++) {
            /*progress_bar += '<li class="mt-4 mb-4">';
            progress_bar += '<div class="d-flex align-items-center mb-1">'+ data[i]['parameter_desc'] +' <span class="text-muted ml-auto">Target '+ data[i]['target'] +' | Bobot '+ data[i]['bobot'] +'</span></div>';
            progress_bar += '<div class="progress" style="height: 1.5rem;">';
            if (data[i]['parameter_id'] == 5) {
              progress_bar_color = (data[i]['realisasi'] < data[i]['target'] ? 'success' : 'danger');
              progress_bar_width = (data[i]['realisasi']/data[i]['target'])*100;
              //progress_bar_width = 0;
              progress_bar_width1 = 100 - progress_bar_width;
              progress_bar += '<div class="progress-bar progress-bar-striped progress-bar-animated bg-info" style="width: '+ progress_bar_width1 +'%">';
              progress_bar += '<span>'+ progress_bar_width1 +'% OK</span></div>';
              progress_bar += '<div class="progress-bar progress-bar-striped progress-bar-animated bg-'+ progress_bar_color +'" style="width: '+ progress_bar_width +'%">';
              progress_bar += '<span>'+ Math.round(data[i]['realisasi']) +'% NOK</span></div></div></li>';
            }
            else{
              progress_bar_color = (data[i]['realisasi'] > data[i]['target'] ? 'success' : 'danger');
              progress_bar_width = (data[i]['realisasi']/data[i]['target'])*100;
              //progress_bar_width = 0;
              progress_bar += '<div class="progress-bar progress-bar-striped progress-bar-animated bg-'+ progress_bar_color +'" style="width: '+ progress_bar_width +'%">';
              progress_bar += '<span>'+ Math.round(data[i]['realisasi']) +'% Complete</span></div></div></li>';
            }*/
            progress_bar += data[i]['progress_bar_element']
          }
          //console.log(progress_bar)
          $('#kpi_layanan').html(progress_bar)
        },
        error: function(jqXhr, json, errorThrown){// this are default for ajax errors
          var errors = jqXhr.responseJSON;
          var errorsHtml = '<div class="alert alert-danger alert-dismissible" role="alert" style="margin-bottom: 0;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Error ' + jqXhr.status + ': ' + errorThrown + '</div>';
          notificationScript("error", "Error " + jqXhr.status, errorThrown);
          $.each(errors['errors'], function (index, value) {
            errorsHtml += '<div class="alert alert-danger alert-dismissible" role="alert" style="margin-bottom: 0;><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + value + '</div>';
            notificationScript("error", "Error Field", value);
          });
        }
      }).done(function(){

      });
  }

  function get_summary_layanan(year_value, month_value) {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        , 'dashboard-type': 1
      }
    });
    $.ajax({
     type:"post",
     url:'/get-summary-layanan',
         data: {year: year_value, month: month_value},
         success: function(data){
          //console.log(data[0])
          var content = '';
          var t_bobot_val = 0;
          for (var i = 0; i < data.length; i++) {
            content += '<tr><td><div class="d-flex align-items-center"><div>';
            content += '<a href="#" class="text-default font-weight-semibold letter-icon-title">'+ data[i]['parameter_desc'] +'</a>';
            if (data[i]['paramater_id'] == 3 || data[i]['paramater_id'] == 8) {
              content += '<div class="text-muted font-size-sm"><i class="icon-arrow-right14 font-size-sm mr-1"></i> Total Transaksi';
              content += '<div class="text-muted font-size-sm"><i class="icon-arrow-right14 font-size-sm mr-1"></i> '+data[i]['realisasi_per_formulasi'][2]['formulasi_desc'];
            }
            else{
              for (var j = 0; j < data[i]['realisasi_per_formulasi'].length; j++) {
                content += '<div class="text-muted font-size-sm"><i class="icon-arrow-right14 font-size-sm mr-1"></i> '+data[i]['realisasi_per_formulasi'][j]['formulasi_desc'];
              }
            }
            content += '</div></div></div></td>';
            content += '<td>';
            if (data[i]['paramater_id'] == 3 || data[i]['paramater_id'] == 8) {
              content += '<p class="font-weight-bold font-size-sm text-center mb-0 mt-3 '
              content += 'text-success">'+new Intl.NumberFormat().format( parseFloat(data[i]['realisasi_per_formulasi'][0]['formulasi_total']) + parseFloat(data[i]['realisasi_per_formulasi'][1]['formulasi_total']) )+'</p>';
              content += '<p class="font-weight-bold font-size-sm text-center mb-0 text-success">'+new Intl.NumberFormat().format(data[i]['realisasi_per_formulasi'][2]['formulasi_total'])+'</p>';
            }
            else{
              for (var j = 0; j < data[i]['realisasi_per_formulasi'].length; j++) {
                content += '<p class="font-weight-bold font-size-sm text-center mb-0'
                content += (j == 0) ? ' mt-3 ' : ' ';
                content += 'text-success">'+new Intl.NumberFormat().format(data[i]['realisasi_per_formulasi'][j]['formulasi_total'])+'</p>';
              }
            }
            content += '</td>';
            content += '<td>';
            content += '<p class="font-weight-bold font-size-lg text-center text-success mb-0">'+data[i]['realisasi'];
            if (data[i]['paramater_id'] != 15 && data[i]['paramater_id'] != 16) { content += data[i]['satuan']; }
            content += '</p>';
            content += '</td>';
            content += '<td>';
            content += '<p class="font-weight-bold font-size-lg text-center text-orange mb-0">'+data[i]['achievement']+'%</p>';
            content += '</td>';
            content += '<td>';
            content += '<p class="font-weight-bold font-size-lg text-center text-info mb-0">'+data[i]['perfomance']+'%</p>';
            content += '</td>';
            content += '<td style="display:none;">';
            content += '<p class="font-weight-bold font-size-lg text-center text-purple mb-0">'+data[i]['persetasi_bobot']+'%</p>';
            content += '</td>';
            content += '</tr>';

            t_bobot_val += data[i]['persetasi_bobot'];
          }
          $('#summary_layanan tbody').html(content)
          $('#t_bobot').html(t_bobot_val)
        },
        error: function(jqXhr, json, errorThrown){// this are default for ajax errors
          var errors = jqXhr.responseJSON;
          var errorsHtml = '<div class="alert alert-danger alert-dismissible" role="alert" style="margin-bottom: 0;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Error ' + jqXhr.status + ': ' + errorThrown + '</div>';
          notificationScript("error", "Error " + jqXhr.status, errorThrown);
          $.each(errors['errors'], function (index, value) {
            errorsHtml += '<div class="alert alert-danger alert-dismissible" role="alert" style="margin-bottom: 0;><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + value + '</div>';
            notificationScript("error", "Error Field", value);
          });
        }
      }).done(function(){
        $('.card-body').unblock(); 
      });
  }

  // On change events
  $("#list_month").change(function() {
    var id = $(this).val();
    tempMonth = id;
    if (id != "" && id != null)
    {
      setTimeout(re_init(), 5000);
    }
  });

  // Func re inisiasi tampulan
  function re_init() {
    get_perfomance_comparison( $("#list_year").val(), $("#list_month").val() );
    get_kpi_information_progress( $("#list_year").val(), $("#list_month").val() );
    get_summary_layanan( $("#list_year").val(), $("#list_month").val() );
  }
  $(document).ready(function() {
    chain3();
  });
</script>
<script type="text/javascript">
  // Define elements
  var columns_basic_element1 = document.getElementById("columns_basic1");
  // Charts configuration
  // Initialize chart
  var columns_basic1 = echarts.init(columns_basic_element1);
  // Chart config
  // Options
  var columns_basic1_option = {
    // Define colors
    color: ["#2ec7c9", "#b6a2de", "#5ab1ef", "#ffb980", "#d87a80"],

    // Global text styles
    textStyle: {
      fontFamily: "Roboto, Arial, Verdana, sans-serif",
      fontSize: 13
    },

    // Chart animation duration
    animationDuration: 750,

    // Setup grid
    grid: {
      left: 0,
      right: 40,
      top: 35,
      bottom: 0,
      containLabel: true
    },

    // Add legend
    legend: {
      data: ["Bobot"],
      itemHeight: 8,
      itemGap: 20,
      textStyle: {
        padding: [0, 5]
      }
    },

    // Add tooltip
    tooltip: {
      trigger: "axis",
      backgroundColor: "rgba(0,0,0,0.75)",
      padding: [10, 15],
      textStyle: {
        fontSize: 13,
        fontFamily: "Roboto, sans-serif"
      }
    },

    // Horizontal axis
    xAxis: [
      {
        type: "category",
        //data: ["Feb - 2020", "Mar - 2020"],
        axisLabel: {
          color: "#333"
        },
        axisLine: {
          lineStyle: {
            color: "#999"
          }
        }
      }
    ],

    // Vertical axis
    yAxis: [
      {
        type: "value",
        axisLabel: {
          color: "#333"
        },
        axisLine: {
          lineStyle: {
            color: "#999"
          }
        },
        splitLine: {
          lineStyle: {
            color: ["#eee"]
          }
        },
        splitArea: {
          show: true,
          areaStyle: {
            color: ["rgba(250,250,250,0.1)", "rgba(0,0,0,0.01)"]
          }
        }
      }
    ],

    // Add series
    series: [
      {
        type: "bar",
        /*markLine: {
             data: [{
               name: 'target',
               yAxis: 150,
               itemStyle: {
                color: '#d87a80'
              }
              }],
        },
        data: [
          {
            value: 152,
            itemStyle: {
              color: '#2ec7c9'
            }
          }
          ,{
            value: 200,
            itemStyle: {
              color: '#b6a2de'
            }
          }
        ],*/
        itemStyle: {
          normal: {
            label: {
              show: true,
              formatter: '{c}%',
              position: "top",
              textStyle: {
                fontWeight: 500
              }
            }
          }
        }
      }
    ]
  }


  function get_perfomance_comparison(year_value, month_value) {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        , 'dashboard-type': 1
      }
    });
    $.ajax({
     type:"post",
     url:'/get-perfomance-comparison',
     data: {year: year_value, month: month_value},
         success: function(data){
          var list_xAxis = trans_val(data, 'desc');
          columns_basic1_option.xAxis[0].data = list_xAxis
          //var list_perfomance = trans_val(data, 'total_perfomance');
          var tempArr = [];
          var colors = ['#b6a2de', '#2ec7c9']
          /*for (var i = 0; i < list_perfomance.length; i++) {
            tempArr.push({
              itemStyle: {
                color: colors[i]
              },
              value: list_perfomance[i]
            })
          }*/
          for (var i = 0; i < data.length; i++) {
            tempArr.push({
              itemStyle: {
                color: colors[i]
              },
              value: data[i]['total_perfomance']
            })
          }
          columns_basic1_option.series[0].data = tempArr
          columns_basic1.setOption(columns_basic1_option, true);
        },
        error: function(jqXhr, json, errorThrown){// this are default for ajax errors
          var errors = jqXhr.responseJSON;
          var errorsHtml = '<div class="alert alert-danger alert-dismissible" role="alert" style="margin-bottom: 0;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Error ' + jqXhr.status + ': ' + errorThrown + '</div>';
          notificationScript("error", "Error " + jqXhr.status, errorThrown);
          $.each(errors['errors'], function (index, value) {
            errorsHtml += '<div class="alert alert-danger alert-dismissible" role="alert" style="margin-bottom: 0;><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + value + '</div>';
            notificationScript("error", "Error Field", value);
          });
        }
      }).done(function(){

      });
    }

  // Resize charts
  // Resize function
  var triggerChartResize = function() {
    columns_basic_element1 && columns_basic1.resize();
  };
  // On sidebar width change
  $(document).on("click", ".sidebar-control, .navbar-toggler", function() {
    setTimeout(function() {
      triggerChartResize();
    }, 0);
  });
  // On window resize
  var resizeCharts;
  window.onresize = function() {
    clearTimeout(resizeCharts);
    resizeCharts = setTimeout(function() {
      triggerChartResize();
    }, 200);
  };
  // Resize charts when hidden element becomes visible
  $('.nav-link[data-toggle="tab"]').on("shown.bs.tab", function(e) {
    triggerChartResize();
  });
</script>
@endsection

@section('content')
<div class="row">
  <div class="col-md-9">
    <!-- Daily sales -->
    <div class="card">
      <div class="card-header header-elements-inline">
        <h5 class="card-title font-weight-bold">{{-- CC 147 --}}{{ Auth::user()->layanans->layanan_desc }} Verifikasi</h5>
        <div class="header-elements">
          <span class="font-weight-bold font-size-lg text-info-600 ml-2" style="display:none;">TOTAL BOBOT | <span id="t_bobot">{{ round($t_bobot ?? 0) }}</span>%</span>
        </div>
      </div>

      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <legend class="font-weight-bold font-size-lg"><i class="icon-law mr-2"></i> Target & Bobot</legend>
            <ul class="list-unstyled mb-0" id="kpi_layanan">
              <li class="mt-4 mb-4">
                <div class="d-flex align-items-center mb-1">Service Level <span class="text-muted ml-auto">Target
                    {{ $kpiObject[0]->target  ?? 0}} | Bobot {{ $kpiObject[0]->bobot  ?? 0}}</span>
                </div>
                <div class="progress" style="height: 1.5rem;">
                  <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{ round($target_service_level  ?? 0) > ($kpiObject[0]->target  ?? 0) ? "success" : "danger" }}" style="width: <?=$width_progressbar_sl ?? 0?>%">
                    <span>{{ round($target_service_level  ?? 0) }}% Complete</span>
                  </div>
                </div>
              </li>

              <li class="mt-4">
                <div class="d-flex align-items-center mb-1">FCR <span class="text-muted ml-auto">Target
                    {{ $kpiObject[1]->target ?? 0 }} | Bobot {{ $kpiObject[1]->bobot ?? 0 }}</span></div>
                <div class="progress" style="height: 1.5rem;">
                  <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{ round($target_fcr  ?? 0) > ($kpiObject[1]->target  ?? 0) ? "success" : "danger" }}" style="width: <?=$width_progressbar_fcr ?? 0 ?>%">
                    <span>{{ round($target_fcr ?? 0 ) }}% Complete</span>
                  </div>
                </div>
              </li>

              <li class="mt-4">
                <div class="d-flex align-items-center mb-1">Rasio Sales <span class="text-muted ml-auto">Target
                    {{ $kpiObject[2]->target ?? 0 }} | Bobot {{ $kpiObject[2]->bobot ?? 0 }}</span></div>
                <div class="progress" style="height: 1.5rem;">
                  <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{ round($target_rasio_sales  ?? 0) > ($kpiObject[2]->target  ?? 0) ? "info" : "danger" }}" style="width: <?=$width_progressbar_rs ?? 0 ?>%">
                    <span>{{ round($target_rasio_sales ?? 0 ) }}% Complete</span>
                  </div>
                </div>
              </li>

              <li class="mt-4">
                <div class="d-flex align-items-center mb-1">CES (by customer) <span class="text-muted ml-auto">Target
                    {{ $kpiObject[3]->target ?? 0 }} | Bobot {{ $kpiObject[3]->bobot ?? 0 }}</span></div>
                <div class="progress" style="height: 1.5rem;">
                  <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{ round($target_ces  ?? 0) > ($kpiObject[3]->target  ?? 0) ? "info" : "danger" }}" style="width: <?=$width_progressbar_ces ?? 0 ?>%">
                    <span>{{ round($target_ces ?? 0 ) }}% Complete</span>
                  </div>
                </div>
              </li>

              <li class="mt-4">
                <div class="d-flex align-items-center mb-1">Quality Layanan <span class="text-muted ml-auto">Target NOK <
                    {{ $kpiObject[4]->target ?? 0 }} | Bobot
                    {{ $kpiObject[4]->bobot ?? 0 }}</span></div>
                <div class="progress" style="height: 1.5rem;">
                  <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{ round(100-($target_quality_layanan  ?? 0)) < ($revert_target ?? 0) ? "info" : "info" }}" style="width: <?= (100-($target_quality_layanan  ?? 0)) == 100 ? 0 : (100-($target_quality_layanan  ?? 0)); ?>%">
                    <span>{{ round(100 - ($target_quality_layanan ?? 0) ) }}% OK</span>
                  </div>

                  <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{ round($target_quality_layanan  ?? 0) <= ($kpiObject[4]->target  ?? 0) ? "success" : "danger" }}" style="width: <?=$target_quality_layanan ?? 0 ?>%">
                    <span>{{ round($target_quality_layanan ?? 0 ) }}% NOK</span>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div class="col-md-8">
            <div class="table-responsive">
              <table class="table text-nowrap" id="summary_layanan">
                <thead>
                  <tr>
                    <td  class="font-weight-bold w-100">Parameter</td>
                    <td align="center" class="font-weight-bold">Real (Sum)</td>
                    <td align="center" class="font-weight-bold">Real (%)</td>
                    <td align="center" class="font-weight-bold">Achv (%)</td>
                    <td align="center" class="font-weight-bold">Perf (%)</td>
                    <td align="center" class="font-weight-bold" style="display:none;">Bobot (%)</td>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <div>
                          <a href="#" class="text-default font-weight-semibold letter-icon-title">Service Level</a>
                          <div class="text-muted font-size-sm"><i class="icon-arrow-right14 font-size-sm mr-1"></i> COF
                          <div class="text-muted font-size-sm"><i class="icon-arrow-right14 font-size-sm mr-1"></i> Call W/ 20 Sec
                          </div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-sm text-center mb-0 mt-3 text-success">
                        {{ isset($input_per_formulasi) ? number_format($input_per_formulasi[0]->total) : 0 }}
                      </p>
                      <p class="font-weight-bold font-size-sm text-center mb-0 text-success">
                        {{ isset($input_per_formulasi) ? number_format($input_per_formulasi[1]->total) : 0 }}
                      </p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center text-success mb-0">
                        {{ isset($service_level) ? optional($service_level)->realisasi : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center text-orange mb-0">
                        {{ isset($service_level) ? optional($service_level)->achievement : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0 text-purple">
                        {{ isset($service_level) ? optional($service_level)->perfomance : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0 text-info">
                        {{ isset($service_level) ? optional($service_level)->persetasi_bobot : 0 }}%</p>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <div>
                          <a href="#" class="text-default font-weight-semibold letter-icon-title">FCR</a>
                          <div class="text-muted font-size-sm"><i class="icon-arrow-right14 font-size-sm mr-1"></i> Closed by Frontliner
                          <div class="text-muted font-size-sm"><i class="icon-arrow-right14 font-size-sm mr-1"></i> Tiket Logic
                          </div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-sm text-center mb-0 mt-3 text-success">
                        {{ isset($input_per_formulasi) ? number_format($input_per_formulasi[2]->total) : 0 }}
                      </p>
                      <p class="font-weight-bold font-size-sm text-center mb-0 text-success">
                        {{ isset($input_per_formulasi) ? number_format($input_per_formulasi[3]->total) : 0 }}
                      </p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center text-success mb-0">
                        {{ isset($fcr) ? optional($fcr)->realisasi : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center text-orange mb-0">
                        {{ isset($fcr) ? optional($fcr)->achievement : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0 text-purple">
                        {{ isset($fcr) ? optional($fcr)->perfomance : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0 text-info">
                        {{ isset($fcr) ? optional($fcr)->persetasi_bobot : 0 }}%</p>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <div>
                          <a href="#" class="text-default font-weight-semibold letter-icon-title">RASIO SALES</a>
                          <div class="text-muted font-size-sm"><i class="icon-arrow-right14 font-size-sm mr-1"></i> Total Transaksi
                          <div class="text-muted font-size-sm"><i class="icon-arrow-right14 font-size-sm mr-1"></i> CWC REGIS
                          </div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-sm text-center mb-0 mt-3 text-success">
                        {{ isset($input_per_formulasi) ? number_format($input_per_formulasi[4]->total + $input_per_formulasi[5]->total) : 0 }}
                      </p>
                      <p class="font-weight-bold font-size-sm text-center mb-0 text-success">
                        {{ isset($input_per_formulasi) ? number_format($input_per_formulasi[6]->total) : 0 }}
                      </p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center text-success mb-0">
                        {{ isset($rasio_sales) ? optional($rasio_sales)->realisasi : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center text-orange mb-0">
                        {{ isset($rasio_sales) ? optional($rasio_sales)->achievement : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0 text-purple">
                        {{ isset($rasio_sales) ? optional($rasio_sales)->perfomance : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0 text-info">
                        {{ isset($rasio_sales) ? optional($rasio_sales)->persetasi_bobot : 0 }}%</p>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <div>
                          <a href="#" class="text-default font-weight-semibold letter-icon-title">CES (by Customer)</a>
                            <div class="text-muted font-size-sm"><i class="icon-arrow-right14 font-size-sm mr-1"></i> Puas
                            <div class="text-muted font-size-sm"><i class="icon-arrow-right14 font-size-sm mr-1"></i> Tidak Puas
                        </Tidak div>
                      </div>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-sm text-center mb-0 mt-3 text-success">
                        {{ isset($input_per_formulasi) ? number_format($input_per_formulasi[7]->total) : 0 }}
                      </p>
                      <p class="font-weight-bold font-size-sm text-center mb-0 text-success">
                        {{ isset($input_per_formulasi) ? number_format($input_per_formulasi[8]->total) : 0 }}
                      </p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center text-success mb-0">
                        {{ isset($ces) ? optional($ces)->realisasi : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center text-orange mb-0">
                        {{ isset($ces) ? optional($ces)->achievement : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0 text-purple">
                        {{ isset($ces) ? optional($ces)->perfomance : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0 text-info">
                        {{ isset($ces) ? optional($ces)->persetasi_bobot : 0 }}%</p>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <div>
                          <a href="#" class="text-default font-weight-semibold letter-icon-title">Quality Layanan</a>
                          <div class="text-muted font-size-sm"><i class="icon-arrow-right14 font-size-sm mr-1"></i> Jumlah Agent OK
                          <div class="text-muted font-size-sm"><i class="icon-arrow-right14 font-size-sm mr-1"></i> Jumlah Agent NOK
                          </div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-sm text-center mb-0 mt-3 text-success">
                        {{ isset($input_per_formulasi) ? number_format($input_per_formulasi[9]->total) : 0 }}
                      </p>
                      <p class="font-weight-bold font-size-sm text-center mb-0 text-success">
                        {{ isset($input_per_formulasi) ? number_format($input_per_formulasi[10]->total) : 0 }}
                      </p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center text-success mb-0">
                        {{ isset($quality_layanan) ? optional($quality_layanan)->realisasi : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center text-orange mb-0">
                        {{ isset($quality_layanan) ? optional($quality_layanan)->achievement : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0 text-purple">
                        {{ isset($quality_layanan) ? optional($quality_layanan)->perfomance : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0 text-info">
                        {{ isset($quality_layanan) ? optional($quality_layanan)->persetasi_bobot : 0 }}%</p>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
    <!-- /daily sales -->
  </div>
  <div class="col-md-3">
    <!-- Basic columns -->
    <div class="card">
      <div class="card-header header-elements-inline">
        <h5 class="card-title">Performance Comparation</h5>
        <div class="header-elements">
          <div class="list-icons">
            {{-- <a class="list-icons-item" data-action="reload"></a> --}}

          </div>
        </div>
      </div>

      <div class="card-body">
        <div class="chart-container">
          <div class="chart has-fixed-height" id="columns_basic1" style="min-height:470px;"></div>
        </div>
      </div>
    </div>
    <!-- /basic columns -->
  </div>
</div>
@endsection
