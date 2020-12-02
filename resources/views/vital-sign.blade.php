@extends('layouts/app')

@section('title', 'Vital Sign '.Auth::user()->layanans->layanan_desc)

@section('liblary')
<script src="assets/js/plugins/visualization/echarts/echarts.min.js"></script>
<script src="assets/js/plugins/forms/styling/uniform.min.js"></script>
<script src="assets/js/plugins/forms/selects/select2.min.js"></script>
<script src="assets/js/plugins/extensions/jquery_ui/interactions.min.js"></script>
@endsection

@section('extra-liblary')
<script src="assets/js/demo_pages/form_select2.js"></script>
<script src="assets/js/demo_pages/form_layouts.js"></script>
<script src="assets/js/plugins/pickers/pickadate/picker.js"></script>
<script src="assets/js/plugins/pickers/pickadate/picker.date.js"></script>
<script src="assets/js/plugins/forms/styling/switch.min.js"></script>
@endsection

@section('script')
{{-- Javascript --}}
<script type="text/javascript">
  var tempYear = 0;
  var tempMonth = 0;
  window['dashboard-type'] = 0; // Default nya Data Real

  function numbersonly(e){
    var unicode=e.charCode? e.charCode : e.keyCode
    if (unicode!=8){ //if the key isn't the backspace key (which we should allow)
        /*if (unicode<48||unicode>57) //if not a number
            return false //disable key press*/
        if ( (unicode>=48 && unicode<=57) || unicode==46) {
          return true
        }
        return false
    }
  }

  $('.equipCatValidation').on('keyup keydown', function(e){
    console.log($(this).val() > 100)
        if ($(this).val() > 100
            && e.keyCode !== 46
            && e.keyCode !== 8
           ) {
           e.preventDefault();
           $(this).val(100);
        }
    });

  // Func Chaining
  function chain1() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
     type:"post",
     url:'{{ route('list-parameter-vs') }}',
         //data: {},
         success: function(data){

          var ahtml = '<option></option>';
          for (var i = 0; i < data.length; i++) {
            ahtml+="<option value='"+data[i]['id']+"'>"+data[i]['parameter_desc']+"</option>"
          }
          $('#select_parameter').html(ahtml);
        },
        error : function(data) {

          console.log("error chain1");
        }
      }).done(function(){

      });
    }
    function chain2(id) {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
       type:"post",
       url:'{{ route('list-formulasi-vs') }}',
       data: {'id_parameter':id},
       success: function(data){
        var ahtml = '<option></option>';
        for (var i = 0; i < data.length; i++) {
          ahtml+="<option value='"+data[i]['id']+"'>"+data[i]['formulasi_desc']+"</option>"
        }
        $('#select_formulasi').html(ahtml);

      },
      error : function(data) {

        console.log("error chain2");

      }
    }).done(function(){

    });
  }
  {{--  FUnc Chain 3 dan Chain 4 pindah ke Layouts App Blade --}}
  // Func-Func Active
  function get_realisasi_monthly(year_value, month_value) {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        , 'dashboard-type': window['dashboard-type']
      }
    });
    $.ajax({
     type:"post",
     url:'{{ route('get-realisasi-monthly-vs') }}',
         data: {year: year_value, month: month_value},
         success: function(data){
          var labelTitles = [
          //'sl_val', 'fcr_val', 'rs_val', 'ces_val', 'ql_val'
          @foreach ($data['parameters_tab'] as $record)
            '{{ $record->id }}_val',
          @endforeach
          ]
          //console.log(data)
          for (var i = 0; i < labelTitles.length; i++) {
            if (data[i]['realisasi'] != null) {
              if (data[i]['satuan'] == 'Mio') {
                realisasi = parseInt(data[i]['realisasi']); 
                satuan = '';
              }
              else{
                realisasi = data[i]['realisasi']; 
                satuan = data[i]['satuan'];
              }
            }
            else{realisasi = 0; satuan = '%'}
            $('#'+labelTitles[i]).html( realisasi+""+satuan )
          }
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

  // Form On Submit
  $('#form-insert-daily').on('submit', function(e){
    e.preventDefault();
    $('#saveBtn').button('loading');
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
     type:"post",
     url:'{{ route('vital-sign-save-daily') }}',
     data: $( this ).serialize(),
     success: function(data){
      $('#saveBtn').button('reset');
      notificationScript("success", "Success", "Successfully submit form.");
      refresh_charts();
      //get_realisasi_monthly();
      $('#insert-new-data').modal('hide');
    },
          error: function(jqXhr, json, errorThrown){// this are default for ajax errors
            $('#saveBtn').button('reset');
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

      });
  // On Change Events
  $("#select_parameter").change(function() {
    var id = $(this).val();
    if (id != "" && id != null)
    {
      chain2(id);
    }
  });
  {{-- FUnc List Year di pindahin ke Layouts App Blade --}}
  $("#list_month").change(function() {
    var id = $(this).val();
    tempMonth = id;
    if (id != "" && id != null)
    {
      get_realisasi_monthly( $("#list_year").val(), $(this).val() )
      $('.date_label').html( moment(new Date($("#list_year").val(), id-1, 1)).format("MMMM - YYYY") )
      //init_chart_value( $("#list_year").val(), $(this).val() );
      setTimeout(init_chart_value( $("#list_year").val(), $(this).val() ), 5000);
    }
  });
  $('#parameter-desc').on('switchChange.bootstrapSwitch', function(event, state) {
    /*console.log(this); // DOM element
    console.log(event); // jQuery event
    console.log(state); // true | false*/
    if (state) { window['dashboard-type'] = 1; }
    else{ window['dashboard-type'] = 0; }
    refresh_charts();
  });
  
  // Button On Click
  function reset_input() {
    $("#form-insert-daily").trigger("reset");
    //$("select").val('').trigger('change');
    $("#select_parameter").val('').trigger('change');
    $("#select_formulasi").html('');
    $('#note_anomaly').prop('required',false);
    $('#note_anomaly').html('');
  }
  // on Close Modal Event
  $('#insert-new-data').on('hidden.bs.modal', function () {
      //var element = $(this).find('form input[name = "id"]')
      //element.remove()
      reset_input();
    });

  // Charts
  @foreach ($data['parameters_tab'] as $record)
    var chart_pid_{{ $record->id }} =echarts.init( document.getElementById('chart_pid_{{ $record->id }}') );
  @endforeach
  /*var columns_basic = echarts.init( document.getElementById('columns_basic') ); // service level
  var line_basic = echarts.init( document.getElementById("line_basic") ); // fcr
  var line_basic1 = echarts.init( document.getElementById("line_basic1") ); // ces
  var line_basic2 = echarts.init( document.getElementById("line_basic2") ); // quality layanan
  var line_stacked = echarts.init( document.getElementById("line_stacked") ); // rasio sales*/

  // Variable penampung html tables nilai daily
  var list_tables = [
  //"summary_table_sl", "summary_table_fcr", "summary_table_rs", "summary_table_ces", "summary_table_ql"
    @php
    $temp_table_id = 0;
    @endphp
    @foreach ($data['parameters_tab'] as $record)
    @php
    if ($temp_table_id == 0){
      $temp_table_id = $record->id;
    }
    @endphp
    "summary_table_{{ $record->id }}",
    @endforeach
  ]

  // Options
  // Yang perlu diisi Legend.data, xAxis.data, Series
  var columns_basic_options = {
    // Define colors
    color: ['#2ec7c9','#b6a2de','#5ab1ef','#ffb980','#d87a80'],
    // Global text styles
    textStyle: {
      fontFamily: 'Roboto, Arial, Verdana, sans-serif',
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
      //data: ['COF', 'Call W 20 Sec'],
      itemHeight: 8,
      itemGap: 20,
      textStyle: {
        padding: [0, 5]
      }
    },
    // Add tooltip
    tooltip: {
      trigger: 'axis',
      backgroundColor: 'rgba(0,0,0,0.75)',
      padding: [10, 15],
      textStyle: {
        fontSize: 13,
        fontFamily: 'Roboto, sans-serif'
      }
    },
    // Horizontal axis
    xAxis: [{
      type: 'category',
      //data: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'],
      axisLabel: {
        color: '#333'
      },
      axisLine: {
        lineStyle: {
          color: '#999'
        }
      },
      splitLine: {
        show: true,
        lineStyle: {
          color: '#eee',
          type: 'dashed'
        }
      }
    }],
    // Vertical axis
    yAxis: [{
      type: 'value',
      axisLabel: {
        color: '#333'
      },
      axisLine: {
        lineStyle: {
          color: '#999'
        }
      },
      splitLine: {
        lineStyle: {
          color: ['#eee']
        }
      },
      splitArea: {
        show: true,
        areaStyle: {
          color: ['rgba(250,250,250,0.1)', 'rgba(0,0,0,0.01)']
        }
      }
    },
    {
      type: 'value',
      min: 0,
      max: 100,
      //interval: 5,
      axisLabel: {
        color: '#333'
      },
      axisLine: {
        lineStyle: {
          color: '#999'
        }
      },
      splitLine: {
        lineStyle: {
          color: ['#eee']
        }
      },
      splitArea: {
        show: true,
        areaStyle: {
          color: ['rgba(250,250,250,0.1)', 'rgba(0,0,0,0.01)']
        }
      }
    },],
    };
  
  // Func untuk Transform data dengan mencari key yang unique lalu
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
  function list_legend(data, value_parameter) {
    window["data_"+value_parameter] =[];
    for (var i = 0; i < data.length; i++) {
      if (data[i]['parameter_desc'] == value_parameter) {
        window["data_"+value_parameter].push(data[i]['value_desc'])
      }
    }
    var uniqueValueDesc = []
    $.each(window["data_"+value_parameter], function(i, el){
      if($.inArray(el, uniqueValueDesc) === -1) uniqueValueDesc.push(el);
    });
    //console.log( uniqueValueDesc )
    return uniqueValueDesc;
  }
  function list_data_series(data, value_parameter, value_desc, type_chart, id_parameter) {
    var tempArr = [];
    window["data_"+value_parameter] =[];
    for (var j = 0; j < value_desc.length; j++) {
      window["data_"+value_parameter+value_desc[j]] =[];
      for (var i = 0; i < data.length; i++) {
        if (data[i]['parameter_desc'] == value_parameter && data[i]['value_desc'] == value_desc[j] ) {
          if (id_parameter == 6 || id_parameter == 17 || id_parameter == 18 || id_parameter == 12) { window["data_"+value_parameter+value_desc[j]].push( (data[i]['value_item']) ) }
          else{ window["data_"+value_parameter+value_desc[j]].push( Math.round(data[i]['value_item']) ) }
        }
      }
      /*for (var h = 0; h < data.length; h++) {
        if ( data[h]['parameter_desc'] == value_parameter ) {
          window["data_"+value_parameter].push( Math.ceil(data[h]['realisasi']) )
        }
      }*/
      //console.log( window["data_"+value_parameter+value_desc[j]] )
      tempArr.push({
        name: value_desc[j],
        type: type_chart,
        data: window["data_"+value_parameter+value_desc[j]],
        itemStyle: {
          normal: {
            label: {
              show: true,
              //formatter: '{c}%',
              formatter: '{c}',
              position: "top",
              textStyle: {
                fontWeight: 500
              }
            }
          }
        }
      })
    }

    var list_day = trans_val(data, 'day');
    for (var i = 0; i < value_desc.length; i++) {
      for (var j = 0; j < data.length; j++) {
        for (var k = 0; k < list_day.length; k++) {
          if ( data[j]['parameter_desc'] == value_parameter && data[j]['value_desc'] == value_desc[i] && data[j]['day'] == list_day[k] ) {
            window["data_"+value_parameter].push( (data[j]['realisasi']) )
          }
        }
      }
    }
    if (id_parameter != 12 && id_parameter != 15 && id_parameter != 16 && id_parameter != 17 && id_parameter != 18 ) {
      tempArr.push({
        name: "Realisasi",
        type: 'line',
        data: window["data_"+value_parameter],
        yAxisIndex: 1,
        color : '#f42',
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
      })
    }
    //console.log(window["data_"+value_parameter])
    return tempArr;
  }

  // mencari value
  function init_chart_value(year_value, month_value){
    //var list_option_charts = [columns_basic_options, line_basic_options, line_stacked_options, line_basic1_options, line_basic2_options]
    var list_option_charts = [
    @foreach ($data['parameters_tab'] as $record)
      columns_basic_options,
    @endforeach
    //columns_basic_options, columns_basic_options, columns_basic_options, columns_basic_options, columns_basic_options
    ]
    //var list_type_charts = ['bar', 'line', 'line', 'line', 'line']
    var list_type_charts = [
    @foreach ($data['parameters_tab'] as $record)
      'bar',
    @endforeach
    //'bar', 'bar', 'bar', 'bar', 'bar'
    ]
    var charts = [
    @foreach ($data['parameters_tab'] as $record)
      chart_pid_{{ $record->id }},
    @endforeach
    //columns_basic, line_basic, line_stacked, line_basic1, line_basic2
    ];
    var param_id = [
    @foreach ($data['parameters_tab'] as $record)
      {{ $record->id }},
    @endforeach
    ];
    for (var i = 0; i < list_option_charts.length; i++) {
      get_monthly_data( $("#list_year").val(), $('#list_month').val(), param_id[i], charts[i], list_option_charts[i], list_type_charts[i], list_tables[i])
    }
    //get_monthly_data( $("#list_year").val(), $('#list_month').val(), 1, charts[0], list_option_charts[0], list_type_charts[0], list_tables[0])
  }

  // Get Data Monthly
  function get_monthly_data(year_value, month_value, parameter_value, chart_element, chart_option, chart_type, table_element) {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        , 'dashboard-type': window['dashboard-type']
      }
    });
    $.ajax({
     type:"post",
     url:'{{ route('get-monthly-data-vs') }}',
         data: {year: year_value, month: month_value, id_parameter: parameter_value},
         success: function(data){
          var list_day = trans_val(data, 'day');
          chart_option.xAxis[0].data = list_day;
          var list_parameter = trans_val(data, 'parameter_desc');
          var tempVal =  list_parameter;
          var tempList = list_legend(data, tempVal);
          chart_option.legend.data = tempList;
          chart_option.series = list_data_series(data, tempVal, tempList, chart_type, parameter_value);
          chart_element.setOption(chart_option, true);

          // Init Populate Tabe Data
          $('#'+table_element).html(populate_table(data, list_day, tempVal, tempList, parameter_value))
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
  
  // Refresh Charts
  function refresh_charts() {
    f_clear_chart();
    chain3();
    //get_realisasi_monthly();
    //setTimeout(init_chart_value( $("#list_year").val(), $('#list_month').val() ), 5000);
  }

  // Resize function
  var triggerChartResize = function() {
    @foreach ($data['parameters_tab'] as $record)
      chart_pid_{{ $record->id }} && chart_pid_{{ $record->id }}.resize();
    @endforeach
      /*columns_basic && columns_basic.resize();
      line_basic && line_basic.resize();
      line_basic1 && line_basic1.resize();
      line_basic2 && line_basic2.resize();
      line_stacked && line_stacked.resize();*/
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

  // Clear Chart Area
  function f_clear_chart(){
    @foreach ($data['parameters_tab'] as $record)
      chart_pid_{{ $record->id }}.clear()
    @endforeach
    /*columns_basic.clear();
    line_basic.clear();
    line_basic1.clear();
    line_basic2.clear();
    line_stacked.clear();*/
  }

  // Populate Tabel Summary Data
  function populate_table(data, list_day, parameter_value, list_value, id_parameter) { // data, list_day, parameter, list formulasi
    var thead_html = '<tr><td align="center" rowspan="2">Formulasi</td>';
    thead_html += '<td align="center"  colspan="'+ list_day.length +'">Date</td>';
    thead_html += '<td align="center" rowspan="2">Total</td></tr>';
    thead_html += '<tr>'
    for (var i = 0; i < list_day.length; i++) {
      thead_html += '<td align="center">'+ list_day[i] +'</td>'
    }
    thead_html += '</tr>'

    var tbody_html = '';
    for (var i = 0; i < list_value.length; i++) {
      window["data_total"+list_value[i]] = [];
      tbody_html += '<tr><td align="left">'+ list_value[i] +'</td>';
      for (var j = 0; j < data.length; j++) {
        if (data[j]['parameter_desc'] == parameter_value && data[j]['value_desc'] == list_value[i] ) {
          var value_item = 0;
          if ( data[j]['value_item'] != "" && data[j]['value_item'] != null ) { 
            if (id_parameter == 6 || id_parameter == 17 || id_parameter == 18 || id_parameter == 12) {
              value_item = parseFloat(data[j]['value_item']) 
            }
            else{
              value_item = parseInt(data[j]['value_item']) 
            }
          }
          window["data_total"+list_value[i]].push(value_item)
          tbody_html += '<td align="center">'+ new Intl.NumberFormat().format(value_item) +'</td>';
        }
      }
      window["counting_total"+list_value[i]] = window["data_total"+list_value[i]].reduce((a, b) => a + b, 0); // reduce untuk iterasi dari value array

      if (id_parameter == 6 || id_parameter == 12) { window["counting_total"+list_value[i]] = window["counting_total"+list_value[i]]/window["data_total"+list_value[i]].length }
      else if(id_parameter == 15 || id_parameter == 16 || id_parameter == 17) { window["counting_total"+list_value[i]] = window["data_total"+list_value[i]][window["data_total"+list_value[i]].length-1] }
      
      tbody_html += '<td align="center">'+ new Intl.NumberFormat().format(window["counting_total"+list_value[i]]) +'</td></tr>'; 
    }
    // looping untuk ngisi tabel total
    var data_total = [];
    for (var i = 0; i < list_value.length; i++) {
      for (var j = 0; j < data.length; j++) {
        for (var k = 0; k < list_day.length; k++) {
          if ( data[j]['parameter_desc'] == parameter_value && data[j]['value_desc'] == list_value[i] && data[j]['day'] == list_day[k] ) {
            var value_item = 0;
            if ( data[j]['value_item'] != "" && data[j]['value_item'] != null ) { 
              if (id_parameter == 6) {
                value_item = parseFloat(data[j]['value_item']) 
              }
              else{
                value_item = parseInt(data[j]['value_item']) 
              }
            }
            if ( data_total[k] == null ) { data_total[k] = value_item }
            else{ data_total[k] += value_item }
          }
        }
      }
    }
    // tbody_html += '<tr><td align="left">Total</td>';
    // for (var i = 0; i < data_total.length; i++) {
    //   tbody_html += '<td align="center">'+ data_total[i] +'</td>';
    // }
    // tbody_html += '<td align="center">'+ data_total.reduce((a, b) => a + b, 0) +'</td></tr>';

    return thead_html + tbody_html;
  }

  function change_table_data(table_id) {
    for (var i = 0; i < list_tables.length; i++) {
      if (table_id != list_tables[i]) { $('#'+list_tables[i]).hide() }
      else { $('#'+list_tables[i]).show() }
    }
  }

  // Document Ready
    $(document).ready(function() {
      chain1();
      chain3();
      reset_input();
      //init_chart_element();
      change_table_data("summary_table_{{ $temp_table_id }}");
      //get_realisasi_monthly()
      $('#input_date').pickadate({format: 'yyyy-mm-dd'});
    });
</script>
@endsection

@section('content')
<!-- Inner container -->
<div class="d-md-flex align-items-md-start">

  <!-- Left sidebar component -->
  <div
    class="sidebar sidebar-light bg-transparent sidebar-component sidebar-component-left wmin-300 border-0 shadow-0 sidebar-expand-md">

    <!-- Sidebar content -->
    <div class="sidebar-content">

      <!-- Navigation -->
      <div class="card">
        <div class="card-body p-0">
          <ul class="nav nav-sidebar mb-2">
            <li class="nav-item-header">
              Vital Sign {{ Auth::user()->layanans->layanan_desc }}
              </li>
              @php
              $i = 0;
              @endphp
              @foreach ($data['parameters_tab'] as $record)
              <li class="nav-item">
                <a href="#pid_{{ $record->id }}" class="nav-link {{ $i == 0 ? 'active' : '' }}" data-toggle="tab" onclick="change_table_data('summary_table_{{ $record->id }}'); ">
                  <i class="icon-cog"></i>
                  {{ $record->parameter_desc }}
                  <span class="badge bg-info badge-pill ml-auto" id="{{ $record->id }}_val">0%</span>
                </a>
              </li>
              @php
              $i++;
              @endphp
              @endforeach
            {{-- </section> --}}
          </ul>
        </div>
      </div>
      <!-- /navigation -->

    </div>
    <!-- /sidebar content -->

  </div>
  <!-- /left sidebar component -->


  <!-- Right content -->
  <div class="tab-content w-100 overflow-auto" id="parameter_tab_content">
    @php
    $i = 0;
    @endphp
    @foreach ($data['parameters_tab'] as $record)
    <div class="tab-pane fade {{ $i == 0 ? 'active' : '' }} show" id="pid_{{ $record->id }}">
      <!-- Basic columns -->
      <div class="card">
        <div class="card-header header-elements-inline">
          <h5 class="card-title">{{ $record->parameter_desc }} [<span class="date_label"></span>]</h5>
          <div class="header-elements">
            @if (Auth::user()->level == 1)
            <button type="button" class="btn bg-info btn-icon ml-3 legitRipple" data-toggle="modal"
              data-target="#insert-new-data"><i class="icon-pencil7"></i></button>
            @endif
          </div>
        </div>

        <div class="card-body">
          <div class="chart-container">
            <div class="chart" id="chart_pid_{{ $record->id }}" style="height: 300px;"></div>
          </div>
        </div>
      </div>
      <!-- /basic columns -->
    </div>
    @php
    $i++;
    @endphp
    @endforeach

    <div class="tab-pane fade" id="target_bobot_management">
      <div class="card">
        <ul class="nav nav-tabs nav-tabs-bottom nav-justified mb-0">
          @foreach ($data['parameters_tab'] as $record)
          <li class="nav-item"><a href="#tab-target-bobot-management" class="nav-link" data-toggle="tab"
              onclick="tab_for_parameter({{ $record->id }})">{{ $record->parameter_desc }}</a>
          </li>
          @endforeach
        </ul>

        <div class="tab-content card-body border-top-0 rounded-top-0 mb-0">
          <div class="tab-pane fade" id="tab-target-bobot-management">
            <div class="row">
              <div class="col-md-4">
                <div class="card-body text-center">
                  <div class="media">
                    <div class="mr-3 align-self-center">
                      <i class="icon-target2 icon-3x text-success-400"></i>
                    </div>

                    <div class="media-body text-right">
                      <h3 class="font-weight-semibold mb-0" id="cur_target"></h3>
                      <span class="text-uppercase font-size-sm text-muted">Current Target</span>
                    </div>
                  </div>
                </div>

                <div class="card-body text-center">
                  <div class="media">
                    <div class="mr-3 align-self-center">
                      <i class="icon-law icon-3x text-info-400"></i>
                    </div>

                    <div class="media-body text-right">
                      <h3 class="font-weight-semibold mb-0" id="cur_bobot"></h3>
                      <span class="text-uppercase font-size-sm text-muted">Current Bobot</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <form id="form-target-bobot">
                  <input type="hidden" name="value_parameter" id="value_parameter" value="0">
                  <fieldset>
                    <legend class="font-weight-semibold p-0">
                      <span class="float-left pt-2">
                        <i class="icon-cog mr-2"></i> Setting Target & Bobot <span id="param_name"></span>
                      </span>


                      <div class="float-right pb-1">
                        <button type="submit" class="btn btn-primary btn-sm">Submit <i
                            class="icon-paperplane"></i></button>
                      </div>
                    </legend>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Target</label>
                          <input type="text" class="form-control" autocomplete="off" name="target" id="target">
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Satuan</label>
                          <select data-placeholder="Pilih Satuan" class="form-control form-control-select2" data-fouc
                            required>
                            <option></option>
                            <option value="percent">Percent (%)</option>
                            <option value="satuan">Satuan</option>
                            <option value="minutes">minutes</option>
                            <option value="mio">Mio</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Bobot</label>
                          <input type="text" class="form-control" autocomplete="off" name="bobot" id="bobot">
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Satuan</label>
                          <select data-placeholder="Pilih Satuan" class="form-control form-control-select2" data-fouc
                            required>
                            <option value="percent">Percent (%)</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </fieldset>
                </form>
              </div>
            </div>
            <!-- /form inputs -->
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
<!-- /right content -->
<!-- /inner container -->

<div class="card">
  <div class="table-responsive" id="parameter_table">
    @foreach ($data['parameters_tab'] as $record)
    <table class="table table-xs table-bordered" id="summary_table_{{ $record->id }}">
    </table>
    @endforeach
  </div>
</div>
<div class="card">
  <div class="table-responsive">
    @foreach ($data['parameters_tab'] as $record)
    <table class="table table-xs table-bordered" id="list_notes_anomaly_{{ $record->id }}">
    </table>
    @endforeach
  </div>
</div>

<!-- Horizontal form modal -->
<div id="insert-new-data" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Insert Data </h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form id="form-insert-daily" class="form-horizontal">
        <div class="modal-body">
          <div class="form-group row">
            <label class="col-form-label col-sm-5">Date</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" autocomplete="off" name="input_date" id="input_date" required>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-5">Parameter</label>
            <div class="col-sm-7">
              <select data-placeholder="Pilih Parameter" class="form-control form-control-select2"
                name="select_parameter" id="select_parameter" data-fouc required>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-5">Item</label>
            <div class="col-sm-7">
              <select data-placeholder="Pilih Item" class="form-control form-control-select2" name="select_formulasi"
                id="select_formulasi" data-fouc required>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-5">Value</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" autocomplete="off" name="value_formulasi" id="value_formulasi"
                onkeypress="return numbersonly(event)" required>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
          <button type="submit" class="btn bg-primary btn-ladda btn-ladda-spinner" data-style="expand-left"
            data-spinner-color="#333" data-spinner-size="20" id="saveBtn"><span
              class="ladda-label">Submit</span></button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /horizontal form modal -->
@endsection
