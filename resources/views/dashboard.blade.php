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
    $(document).ready(function() {
      get_perfomance_comparison();
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
  

  function get_perfomance_comparison() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
     type:"post",
     url:'/get-perfomance-comparison',
         //data: {year: year_value, month: month_value, id_parameter: parameter_value},
         success: function(data){
          var list_xAxis = trans_val(data, 'desc');
          columns_basic1_option.xAxis[0].data = list_xAxis
          var list_perfomance = trans_val(data, 'total_perfomance');
          var tempArr = [];
          var colors = ['#b6a2de', '#2ec7c9']
          for (var i = 0; i < list_perfomance.length; i++) {
            tempArr.push({
              itemStyle: {
                color: colors[i]
              },
              value: list_perfomance[i]
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
        <h5 class="card-title font-weight-bold">CC 147</h5>
        <div class="header-elements">
          <span class="font-weight-bold font-size-lg text-info-600 ml-2">TOTAL BOBOT | {{ round($t_bobot ?? 0) }}%</span>
        </div>
      </div>

      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <legend class="font-weight-bold font-size-lg"><i class="icon-law mr-2"></i> Target & Bobot</legend>
            <ul class="list-unstyled mb-0">
              <li class="mt-4 mb-4">
                <div class="d-flex align-items-center mb-1">Service Level <span class="text-muted ml-auto">Target
                    {{ $kpiObject[0]->target  ?? 0}} | Bobot {{ $kpiObject[0]->bobot  ?? 0}}</span>
                </div>
                <div class="progress" style="height: 1.5rem;">
                  <div class="progress-bar bg-{{ round($target_service_level  ?? 0) > 50 ? "info" : "danger" }}" style="width: <?=$target_service_level ?? 0?>%">
                    <span>{{ round($target_service_level  ?? 0) }}% Complete</span>
                  </div>
                </div>
              </li>

              <li class="mt-4">
                <div class="d-flex align-items-center mb-1">FCR <span class="text-muted ml-auto">Target
                    {{ $kpiObject[1]->target ?? 0 }} | Bobot {{ $kpiObject[1]->bobot ?? 0 }}</span></div>
                <div class="progress" style="height: 1.5rem;">
                  <div class="progress-bar bg-{{ round($target_fcr  ?? 0) > 50 ? "info" : "danger" }}" style="width: <?=$target_fcr ?? 0 ?>%">
                    <span>{{ round($target_fcr ?? 0 ) }}% Complete</span>
                  </div>
                </div>
              </li>

              <li class="mt-5">
                <div class="d-flex align-items-center mb-1">Rasio Sales <span class="text-muted ml-auto">Target
                    {{ $kpiObject[2]->target ?? 0 }} | Bobot {{ $kpiObject[2]->bobot ?? 0 }}</span></div>
                <div class="progress" style="height: 1.5rem;">
                  <div class="progress-bar bg-{{ round($target_rasio_sales  ?? 0) > 50 ? "info" : "danger" }}" style="width: <?=$target_rasio_sales ?? 0 ?>%">
                    <span>{{ round($target_rasio_sales ?? 0 ) }}% Complete</span>
                  </div>
                </div>
              </li>

              <li class="mt-4">
                <div class="d-flex align-items-center mb-1">CES (by customer) <span class="text-muted ml-auto">Target
                    {{ $kpiObject[3]->target ?? 0 }} | Bobot {{ $kpiObject[3]->bobot ?? 0 }}</span></div>
                <div class="progress" style="height: 1.5rem;">
                  <div class="progress-bar bg-{{ round($target_ces  ?? 0) > 50 ? "info" : "danger" }}" style="width: <?=$target_ces ?? 0 ?>%">
                    <span>{{ round($target_ces ?? 0 ) }}% Complete</span>
                  </div>
                </div>
              </li>

              <li class="mt-4">
                <div class="d-flex align-items-center mb-1">Quality Layanan <span class="text-muted ml-auto">Target
                    {{ $kpiObject[4]->target ?? 0 }} | Bobot
                    {{ $kpiObject[4]->bobot ?? 0 }}</span></div>
                <div class="progress" style="height: 1.5rem;">
                  <div class="progress-bar bg-{{ round($target_quality_layanan  ?? 0) > 50 ? "info" : "danger" }}" style="width: <?=$target_quality_layanan ?? 0 ?>%">
                    <span>{{ round($target_quality_layanan ?? 0 ) }}% Complete</span>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div class="col-md-8">
            <div class="table-responsive">
              <table class="table text-nowrap">
                <thead>
                  <tr>
                    <td  class="font-weight-bold w-100">Parameter</td>
                    <td align="center" class="font-weight-bold">Real (Sum)</td>
                    <td align="center" class="font-weight-bold">Real (%)</td>
                    <td align="center" class="font-weight-bold">Achv (%)</td>
                    <td align="center" class="font-weight-bold">Perf (%)</td>
                    <td align="center" class="font-weight-bold">Bobot (%)</td>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <div>
                          <a href="#" class="text-default font-weight-semibold letter-icon-title">Service Level</a>
                          <div class="text-muted font-size-sm"><i class="icon-arrow-right14 font-size-sm mr-1"></i> FCR
                          <div class="text-muted font-size-sm"><i class="icon-arrow-right14 font-size-sm mr-1"></i> Call
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
                      <p class="font-weight-bold font-size-lg text-center mb-0">
                        {{ isset($service_level) ? optional($service_level)->realisasi : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0">
                        {{ isset($service_level) ? optional($service_level)->achievement : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0">
                        {{ isset($service_level) ? optional($service_level)->perfomance : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0">
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
                      <p class="font-weight-bold font-size-lg text-center mb-0">
                        {{ isset($fcr) ? optional($fcr)->realisasi : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0">
                        {{ isset($fcr) ? optional($fcr)->achievement : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0">
                        {{ isset($fcr) ? optional($fcr)->perfomance : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0">
                        {{ isset($fcr) ? optional($fcr)->persetasi_bobot : 0 }}%</p>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <div>
                          <a href="#" class="text-default font-weight-semibold letter-icon-title">Rasio Sales</a>
                          <div class="text-muted font-size-sm"><i class="icon-arrow-right14 font-size-sm mr-1"></i> Transaksi Add On
                          <div class="text-muted font-size-sm"><i class="icon-arrow-right14 font-size-sm mr-1"></i> Transaksi PSB
                          <div class="text-muted font-size-sm"><i class="icon-arrow-right14 font-size-sm mr-1"></i> CWC REGIS
                          </div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-sm text-center mb-0 mt-3 text-success">
                        {{ isset($input_per_formulasi) ? number_format($input_per_formulasi[4]->total) : 0 }}
                      </p>
                      <p class="font-weight-bold font-size-sm text-center mb-0 text-success">
                        {{ isset($input_per_formulasi) ? number_format($input_per_formulasi[5]->total) : 0 }}
                      </p>
                      <p class="font-weight-bold font-size-sm text-center mb-0 text-success">
                        {{ isset($input_per_formulasi) ? number_format($input_per_formulasi[6]->total) : 0 }}
                      </p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0">
                        {{ isset($rasio_sales) ? optional($rasio_sales)->realisasi : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0">
                        {{ isset($rasio_sales) ? optional($rasio_sales)->achievement : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0">
                        {{ isset($rasio_sales) ? optional($rasio_sales)->perfomance : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0">
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
                      <p class="font-weight-bold font-size-lg text-center mb-0">
                        {{ isset($ces) ? optional($ces)->realisasi : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0">
                        {{ isset($ces) ? optional($ces)->achievement : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0">
                        {{ isset($ces) ? optional($ces)->perfomance : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0">
                        {{ isset($ces) ? optional($ces)->persetasi_bobot : 0 }}%</p>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <div>
                          <a href="#" class="text-default font-weight-semibold letter-icon-title">Quality Layanan</a>
                          <div class="text-muted font-size-sm"><i class="icon-arrow-right14 font-size-sm mr-1"></i> Agent OK
                          <div class="text-muted font-size-sm"><i class="icon-arrow-right14 font-size-sm mr-1"></i> Agent NOK
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
                      <p class="font-weight-bold font-size-lg text-center mb-0">
                        {{ isset($quality_layanan) ? optional($quality_layanan)->realisasi : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0">
                        {{ isset($quality_layanan) ? optional($quality_layanan)->achievement : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0">
                        {{ isset($quality_layanan) ? optional($quality_layanan)->perfomance : 0 }}%</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0">
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
            <a class="list-icons-item" data-action="reload"></a>

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
