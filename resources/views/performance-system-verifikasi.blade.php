@extends('layouts/app')

@section('title', 'Perfomance System / Verifikasi')

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
  var tempYear = 0;
  var tempMonth = 0;

  //Inisiasi BlockUI
  $('#table-perfomance').block({ 
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

  // Func-Func Active
  function get_perfomance_system_verifikasi_layanan(year_value, month_value) {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
     type:"post",
     url:'/get-perfomance-system-verifikasi-layanan',
         data: {year: year_value, month: month_value},
         success: function(data){
          console.log(data)
          var tbody_html = '';
          var sum_persetasi_bobot = 0, sum_persetasi_bobot_verifikasi = 0;
          for (var i = 0; i < data.length; i++) {
            data[i]
            tbody_html += '<tr>';
            tbody_html += '<td>'+ data[i]['parameter_desc'] +'</td>';
            tbody_html += '<td>'+ data[i]['satuan'] +'</td>';
            tbody_html += '<td>'+ data[i]['target'] +'</td>';
            tbody_html += '<td>'+ data[i]['bobot'] +'</td>';
            tbody_html += '<td>'+ data[i]['realisasi'] + data[i]['satuan'] +'</td>';
            tbody_html += '<td>'+ data[i]['realisasi_verifikasi'] + data[i]['satuan'] +'</td>';
            tbody_html += '<td>'+ data[i]['achievement'] +'%</td>';
            tbody_html += '<td>'+ data[i]['achievement_verifikasi'] +'%</td>';
            tbody_html += '<td>'+ data[i]['persetasi_bobot'] +'%</td>';
            tbody_html += '<td>'+ data[i]['persetasi_bobot_verifikasi'] +'%</td>';
            tbody_html += '</tr>';
            sum_persetasi_bobot += data[i]['persetasi_bobot'];
            sum_persetasi_bobot_verifikasi += data[i]['persetasi_bobot_verifikasi'];
          }
          var tfoot_html = '<tr><td colspan="8" class="text-center">Total</td><td>'+ sum_persetasi_bobot +'%</td><td>'+ sum_persetasi_bobot_verifikasi +'%</td></tr>'
          $('#table-perfomance tbody').html(tbody_html);
          $('#table-perfomance tfoot').html(tfoot_html);

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
        $('#table-perfomance').unblock(); 
      });
    }

  // On Change Events
  $("#list_month").change(function() {
    var id = $(this).val();
    tempMonth = id;
    if (id != "" && id != null)
    {
      get_perfomance_system_verifikasi_layanan( $("#list_year").val(), $(this).val() )
      $('.date_label').html( moment(new Date($("#list_year").val(), id-1, 1)).format("MMMM - YYYY") )
      
    }
  });

  // Document Ready
  $(document).ready(function() {
    chain3();
  });

</script>
@endsection

@section('content')
<div class="card">
  <div class="table-responsive">
    <table class="table table-bordered table-striped" id="table-perfomance">
      <thead>
        <tr class="font-weight-bold">
          <td colspan="4">{{ Auth::user()->layanans->layanan_desc }} [<span class="date_label"></span>]</td>
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
        {{-- <tr>
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
        </tr> --}}
      </tbody>
      <tfoot class="font-weight-bold">
        {{-- <tr>
          <td colspan="8" class="text-center">Total</td>
          <td>86%</td>
          <td>98%</td>
        </tr> --}}
      </tfoot>
    </table>
  </div>
</div>
@endsection
