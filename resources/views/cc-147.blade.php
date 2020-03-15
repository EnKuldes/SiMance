@extends('layouts/app')

@section('title', 'CC 147')

@section('liblary')
<script src="assets/js/plugins/visualization/echarts/echarts.min.js"></script>
<script src="assets/js/plugins/forms/styling/uniform.min.js"></script>
<script src="assets/js/plugins/forms/selects/select2.min.js"></script>
<script src="assets/js/plugins/extensions/jquery_ui/interactions.min.js"></script>
@endsection

@section('extra-liblary')
<script src="assets/js/demo_pages/charts/echarts/columns_waterfalls.js"></script>
<script src="assets/js/demo_pages/charts/echarts/lines.js"></script>
<script src="assets/js/demo_pages/form_select2.js"></script>
<script src="assets/js/demo_pages/form_layouts.js"></script>
@endsection

@section('script')
{{-- Javascript --}}
<script type="text/javascript">
  function isNumberKey(evt)
  {
     var charCode = (evt.which) ? evt.which : event.keyCode
     if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

     return true;
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
     url:'/list-parameter',
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
       url:'/list-formulasi',
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
     url:'/daily/save',
     data: $( this ).serialize(),
     success: function(data){
      $('#saveBtn').button('reset');
      notificationScript("success", "Success", "Successfully submit form.");
      refresh_charts();
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
    // Document Ready
    $(document).ready(function() {
      chain1();
      reset_input();
    });
  // On Change Events
  $("#select_parameter").change(function() {
    var id = $(this).val();
    if (id != "" && id != null)
    {
      chain2(id);
    }
  });
  // Button On Click
  function reset_input() {
    $("#form-insert-daily").trigger("reset");
    $("select").val('').trigger('change');
    $("#select_formulasi").html('');
  }
  // on Close Modal Event
  $('#insert-new-data').on('hidden.bs.modal', function () {
      //var element = $(this).find('form input[name = "id"]')
      //element.remove()
      reset_input();
    });
  // Refresh Charts
  function refresh_charts() {
    notificationScript("info", "Info", "Onprogress.");
  }
</script>
@endsection

@section('content')
<div class="navbar navbar-expand-lg navbar-light navbar-component rounded">
  <div class="text-center d-lg-none w-100">
    <button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-filter">
      <i class="icon-unfold mr-2"></i>
      Filters
    </button>
  </div>

  <div class="navbar-collapse collapse" id="navbar-filter">
    <span class="navbar-text font-weight-semibold mr-3">
      Filter:
    </span>

    <ul class="navbar-nav flex-wrap">
    </ul>

    <span class="navbar-text font-weight-semibold mr-3 ml-md-auto">
    </span>

    <ul class="navbar-nav flex-wrap">
      <select data-placeholder="This Month" class="form-control select" data-fouc id="list_month">
        <option></option>
        <option value="jan">Jan</option>
        <option value="feb">Feb</option>
        <option value="mar">Mar</option>
      </select>
    </ul>

    <ul class="navbar-nav flex-wrap">
      <select data-placeholder="This Yeaar" class="form-control select" data-fouc id="list_year">
        <option></option>
        <option value="2019">2019</option>
        <option value="2020">2020</option>
      </select>
    </ul>
  </div>
</div>

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
            <li class="nav-item-header">Parameter</li>
            <li class="nav-item">
              <a href="#service_level" class="nav-link active" data-toggle="tab">
                <i class="icon-cog"></i>
                Service Level
                <span class="badge bg-info badge-pill ml-auto">29%</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="#fcr" class="nav-link" data-toggle="tab">
                <i class="icon-watch2"></i>
                FCR
                <span class="badge bg-info badge-pill ml-auto">21%</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="#rasio_sales" class="nav-link" data-toggle="tab">
                <i class="icon-clipboard5"></i>
                Rasio Sales
                <span class="badge bg-info badge-pill ml-auto">29%</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="#ces" class="nav-link" data-toggle="tab">
                <i class="icon-search4"></i>
                CES (by customer)
                <span class="badge bg-info badge-pill ml-auto">16%</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="#quality_layanan" class="nav-link" data-toggle="tab">
                <i class="icon-thumbs-up2"></i>
                Quality Layanan
                <span class="badge bg-info badge-pill ml-auto">66%</span>
              </a>
            </li>
            <li class="nav-item-header">Resource</li>
            <li class="nav-item">
              <a href="#target_bobot_management" class="nav-link" data-toggle="tab">
                <i class="icon-target2"></i><i class="icon-law"></i>
                Target & Bobot Management
              </a>
            </li>
          </ul>
        </div>
      </div>
      <!-- /navigation -->

    </div>
    <!-- /sidebar content -->

  </div>
  <!-- /left sidebar component -->


  <!-- Right content -->
  <div class="tab-content w-100 overflow-auto">
    <div class="tab-pane fade active show" id="service_level">
      <!-- Basic columns -->
      <div class="card">
        <div class="card-header header-elements-inline">
          <h5 class="card-title">Service Level [<?php echo date("F - Y"); ?>]</h5>
          <div class="header-elements">
            <button type="button" class="btn bg-blue btn-icon legitRipple ml-3"><i class="icon-sync"></i></button>
            <button type="button" class="btn bg-pink-400 btn-icon ml-3 legitRipple" data-toggle="modal"
              data-target="#insert-new-data"><i class="icon-pencil7"></i></button>
          </div>
        </div>

        <div class="card-body">
          <div class="chart-container">
            <div class="chart has-fixed-height" id="columns_basic"></div>
          </div>
        </div>
      </div>
      <!-- /basic columns -->
    </div>

    <div class="tab-pane fade" id="fcr">
      <!-- Basic line -->
      <div class="card">
        <div class="card-header header-elements-inline">
          <h5 class="card-title">FCR [<?php echo date("F - Y"); ?>]</h5>
          <div class="header-elements">
            <button type="button" class="btn bg-blue btn-icon legitRipple ml-3"><i class="icon-sync"></i></button>
            <button type="button" class="btn bg-pink-400 btn-icon ml-3 legitRipple" data-toggle="modal"
              data-target="#insert-new-data"><i class="icon-pencil7"></i></button>
          </div>
        </div>

        <div class="card-body">
          <div class="chart-container">
            <div class="chart has-fixed-height" id="line_basic"></div>
          </div>
        </div>
      </div>
      <!-- /basic line -->
    </div>

    <div class="tab-pane fade" id="rasio_sales">
      <!-- Stacked lines -->
      <div class="card">
        <div class="card-header header-elements-inline">
          <h5 class="card-title">Rasio Sales [<?php echo date("F - Y"); ?>]</h5>
          <div class="header-elements">
            <button type="button" class="btn bg-blue btn-icon legitRipple ml-3"><i class="icon-sync"></i></button>
            <button type="button" class="btn bg-pink-400 btn-icon ml-3 legitRipple" data-toggle="modal"
              data-target="#insert-new-data"><i class="icon-pencil7"></i></button>
          </div>
        </div>

        <div class="card-body">
          <div class="chart-container">
            <div class="chart has-fixed-height" id="line_stacked"></div>
          </div>
        </div>
      </div>
      <!-- /stacked lines -->
    </div>

    <div class="tab-pane fade" id="ces">
      <!-- Basic line -->
      <div class="card">
        <div class="card-header header-elements-inline">
          <h5 class="card-title">CES (by customer) [<?php echo date("F - Y"); ?>]</h5>
          <div class="header-elements">
            <button type="button" class="btn bg-blue btn-icon legitRipple ml-3"><i class="icon-sync"></i></button>
            <button type="button" class="btn bg-pink-400 btn-icon ml-3 legitRipple" data-toggle="modal"
              data-target="#insert-new-data"><i class="icon-pencil7"></i></button>
          </div>
        </div>

        <div class="card-body">
          <div class="chart-container">
            <div class="chart has-fixed-height" id="line_basic1"></div>
          </div>
        </div>
      </div>
      <!-- /basic line -->
    </div>

    <div class="tab-pane fade" id="quality_layanan">
      <!-- Basic line -->
      <div class="card">
        <div class="card-header header-elements-inline">
          <h5 class="card-title">Quality Layanan [<?php echo date("F - Y"); ?>]</h5>
          <div class="header-elements">
            <button type="button" class="btn bg-blue btn-icon legitRipple ml-3"><i class="icon-sync"></i></button>
            <button type="button" class="btn bg-pink-400 btn-icon ml-3 legitRipple" data-toggle="modal"
              data-target="#insert-new-data"><i class="icon-pencil7"></i></button>
          </div>
        </div>

        <div class="card-body">
          <div class="chart-container">
            <div class="chart has-fixed-height" id="line_basic2"></div>
          </div>
        </div>
      </div>
      <!-- /basic line -->
    </div>

    <div class="tab-pane fade" id="target_bobot_management">
      <div class="card">
        <ul class="nav nav-tabs nav-tabs-bottom nav-justified mb-0">
          <li class="nav-item"><a href="#tab-service_level" class="nav-link active" data-toggle="tab">Service Level</a>
          </li>
          <li class="nav-item"><a href="#tab-fcr" class="nav-link" data-toggle="tab">FCR</a></li>
          <li class="nav-item"><a href="#tab-rasio_sales" class="nav-link" data-toggle="tab">Rasio Sales</a>
          </li>
          <li class="nav-item"><a href="#tab-ces" class="nav-link" data-toggle="tab">CES (by customer)</a></li>
          <li class="nav-item"><a href="#tab-quality_layanan" class="nav-link" data-toggle="tab">Quality Layanan</a>
          </li>
        </ul>

        <div class="tab-content card-body border-top-0 rounded-top-0 mb-0">
          <div class="tab-pane fade active show" id="tab-service_level">
            <div class="row">
              <div class="col-md-4">
                <div class="card-body text-center">
                  <div class="media">
                    <div class="mr-3 align-self-center">
                      <i class="icon-target2 icon-3x text-success-400"></i>
                    </div>

                    <div class="media-body text-right">
                      <h3 class="font-weight-semibold mb-0">95%</h3>
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
                      <h3 class="font-weight-semibold mb-0">25%</h3>
                      <span class="text-uppercase font-size-sm text-muted">Current Bobot</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <form action="#">
                  <fieldset>
                    <legend class="font-weight-semibold p-0">
                      <span class="float-left pt-2">
                        <i class="icon-cog mr-2"></i> Setting Target & Bobot Service Level
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

          <div class="tab-pane fade" id="tab-fcr">
            <div class="row">
              <div class="col-md-4">
                <div class="card-body text-center">
                  <div class="media">
                    <div class="mr-3 align-self-center">
                      <i class="icon-target2 icon-3x text-success-400"></i>
                    </div>

                    <div class="media-body text-right">
                      <h3 class="font-weight-semibold mb-0">95%</h3>
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
                      <h3 class="font-weight-semibold mb-0">25%</h3>
                      <span class="text-uppercase font-size-sm text-muted">Current Bobot</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <form action="#">
                  <fieldset>
                    <legend class="font-weight-semibold p-0">
                      <span class="float-left pt-2">
                        <i class="icon-cog mr-2"></i> Setting Target & Bobot FCR
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

          <div class="tab-pane fade" id="tab-rasio_sales">
            DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg whatever.
          </div>

          <div class="tab-pane fade" id="tab-ces">
            DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg whatever.
          </div>

          <div class="tab-pane fade" id="tab-quality_layanan">
            DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg whatever.
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<!-- /right content -->

</div>
<!-- /inner container -->

<!-- Horizontal form modal -->
<div id="insert-new-data" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Insert Data [ <?php echo date("d - M, Y"); ?> ]</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form id="form-insert-daily" class="form-horizontal">
        <div class="modal-body">
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
              <input type="number" class="form-control equipCatValidation" autocomplete="off" name="value_formulasi" id="value_formulasi"
                maxlength="3">
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
