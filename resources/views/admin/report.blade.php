@extends('layouts/app')

@section('title', 'Report')

@section('liblary')
<script src="assets/js/plugins/visualization/echarts/echarts.min.js"></script>
<script src="assets/js/plugins/forms/styling/uniform.min.js"></script>
<script src="assets/js/plugins/forms/selects/select2.min.js"></script>
<script src="assets/js/plugins/extensions/jquery_ui/interactions.min.js"></script>
@endsection

@section('extra-liblary')
<script src="assets/js/demo_pages/picker_date.js"></script>
<script src="assets/js/demo_pages/form_select2.js"></script>
<script src="assets/js/demo_pages/form_layouts.js"></script>
@endsection

@section('script')
<script type="text/javascript">
  var id_layanan = [
  @foreach ($list_layanan as $layanan)
    {{ $layanan->id }},
  @endforeach
  ];
  var layanan_kpi_param = [
  @foreach ($list_layanan as $layanan)
    'lay_{{ $layanan->id }}_kpi_param',
  @endforeach
  ];
  var layanan_summary = [
  @foreach ($list_layanan as $layanan)
    'lay_{{ $layanan->id }}_summary',
  @endforeach
  ];

  $('tbody').block({
      message: '<i class="icon-spinner4 spinner"></i>',
      overlayCSS: {
          backgroundColor: '#fff',
          opacity: 0.95,
          cursor: 'wait'
      },
      css: {
          border: 0,
          padding: 0,
          backgroundColor: 'transparent'
      }
  });

  // Func
  function get_kpi_information_progress(year_value, month_value, id_layanan, idx) {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
     type:"post",
     url:'/get-kpi-information-progress',
         data: {year: year_value, month: month_value, layanan: id_layanan},
         success: function(data){
          content = '';
          for (var i = 0; i < data.length; i++) {
            content += '<tr><td><h6 class="mb-0">'+ data[i]['parameter_desc'] +'</h6></td>';
            content += '<td>'+ data[i]['satuan'] +'</td>';
            content += '<td><span class="font-weight-semibold">'+ data[i]['target'] +'</span></td>';
            content += '<td><span class="font-weight-semibold">'+ data[i]['bobot'] +'</span></td></tr>';
          }
          $('#'+ layanan_kpi_param[idx] +' tbody').html(content)
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

  function get_summary_layanan(year_value, month_value, id_layanan, idx) {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
     type:"post",
     url:'/get-summary-layanan',
         data: {year: year_value, month: month_value, layanan: id_layanan},
         success: function(data){
          //console.log(data[0])
          var content = '';
          for (var i = 0; i < data.length; i++) {
            content += '<tr><td><h6 class="mb-0">'+ data[i]['parameter_desc'] +'</h6></td>';
            content += '<td><span class="font-weight-semibold">'+ data[i]['realisasi'] + data[i]['satuan'] +'</span></td>';
            content += '<td><span class="font-weight-semibold">'+ data[i]['achievement'] +'%</span></td>';
            content += '<td><span class="font-weight-semibold">'+ data[i]['perfomance'] +'%</span></td>';
            content += '</tr>';
          }
          $('#'+ layanan_summary[idx] +' tbody').html(content)
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
        $('tbody').unblock();
      });
  }

  // On change events
  $("#list_month").change(function() {
    var id = $(this).val();
    tempMonth = id;
    if (id != "" && id != null)
    {
      $('#report_date').html( moment().set({'year': $('#list_year'), 'month': id-1}).format("MMMM YYYY") )
      setTimeout(re_init(), 5000);
    }
  });

  // Func re inisiasi tampulan
  function re_init() {
    $('tbody').block({
        message: '<i class="icon-spinner4 spinner"></i>',
        overlayCSS: {
            backgroundColor: '#fff',
            opacity: 0.95,
            cursor: 'wait'
        },
        css: {
            border: 0,
            padding: 0,
            backgroundColor: 'transparent'
        }
    });
    for (var i = 0; i < id_layanan.length; i++) {
      get_kpi_information_progress( $("#list_year").val(), $("#list_month").val(), id_layanan[i], i );
      get_summary_layanan( $("#list_year").val(), $("#list_month").val(), id_layanan[i], i );
    }
  }
  $(document).ready(function() {
    chain3();
  });
  
</script>
@endsection

@section('content')
<div class="card">
  <div class="card-header bg-transparent header-elements-inline">
    <h6 class="card-title">Report Performance</h6>
    <div class="header-elements">
      <button type="button" class="btn btn-light btn-sm ml-3 legitRipple" onclick="window.print()"><i
          class="icon-printer mr-2"></i>
        Print</button>
    </div>
  </div>

  <div class="card-body">
    <div class="row">
      <div class="col-sm-6">
        <div class="mb-4">
          <img src="assets/images/logo.png" class="mb-3 mt-2" alt="" style="width: 120px;">
        </div>
      </div>

      <div class="col-sm-6">
        <div class="mb-4">
          <div class="text-sm-right">
            <h4 class="text-primary mb-2 mt-md-2">Report #49029</h4>
            <ul class="list list-unstyled mb-0">
              <li>Month: <span class="font-weight-semibold" id="report_date"></span></li>
              {{--<li>Due date: <span class="font-weight-semibold">May 12, 2015</span></li>--}}
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>


  @foreach ($list_layanan as $layanan)
  <p>
    <h1 class="ml-2 mb-0">Layanan {{ $layanan->layanan_desc }}</h1>
  </p>

  <div class="row mb-2">
    <div class="col-md-5">
      <div class="table-responsive">
        <table class="table table-sm table-bordered" id="lay_{{ $layanan->id }}_kpi_param">
          <thead>
            <tr>
              <th colspan="4">Target KPI</th>
            </tr>
            <tr>
              <th>Parameter</th>
              <th>Satuan</th>
              <th>Target</th>
              <th>Bobot</th>
            </tr>
          </thead>
          <tbody>
            
          </tbody>
        </table>
      </div>
    </div>

    <div class="col-md-7">
      <div class="table-responsive">
        <table class="table table-sm table-bordered" id="lay_{{ $layanan->id }}_summary">
          <thead>
            <tr>
              <th colspan="4">Performance</th>
            </tr>
            <tr>
              <th>Parameter</th>
              <th>Real</th>
              <th>Achv</th>
              <th>Perf</th>
            </tr>
          </thead>
          <tbody>
            
          </tbody>
        </table>
      </div>
    </div>
  </div>
  @endforeach
</div>
@endsection
