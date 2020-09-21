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
  // function re_init() {
  //   $('tbody').block({
  //       message: '<i class="icon-spinner4 spinner"></i>',
  //       overlayCSS: {
  //           backgroundColor: '#fff',
  //           opacity: 0.95,
  //           cursor: 'wait'
  //       },
  //       css: {
  //           border: 0,
  //           padding: 0,
  //           backgroundColor: 'transparent'
  //       }
  //   });

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
  {{-- <div class="card-header header-elements-inline">
    <h5 class="card-title">Bordered striped</h5>
    <div class="header-elements">
      <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
                <a class="list-icons-item" data-action="reload"></a>
                <a class="list-icons-item" data-action="remove"></a>
              </div>
            </div>
  </div> --}}

  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead>
        <tr class="font-weight-bold">
          <td colspan="4">Frontline CC147</td>
          <td colspan="2">Realisasi</td>
          <td colspan="2">Achievement</td>
          <td colspan="2">%Bobot</td>
        </tr>
        <tr class="font-weight-semibold">
          <td>Parameter</td>
          <td>Satuan</td>
          <td>Target</td>
          <td>Bobot</td>
          <td>By Aplikasi</td>
          <td>By Verifikasi</td>
          <td>By Aplikasi</td>
          <td>By Verifikasi</td>
          <td>By Aplikasi</td>
          <td>By Verifikasi</td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Service Level</td>
          <td>%</td>
          <td>>=95%</td>
          <td>20%</td>
          <td>85%</td>
          <td>94%</td>
          <td>85%</td>
          <td>94%</td>
          <td>85%</td>
          <td>94%</td>
        </tr>
      </tbody>
      <tfoot class="font-weight-bold">
        <tr>
          <td colspan="8" class="text-center">Total</td>
          <td>86%</td>
          <td>98%</td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
@endsection
