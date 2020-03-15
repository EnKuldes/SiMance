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
      <select data-placeholder="This Month" class="form-control select" data-fouc>
        <option></option>
        <option value="jan">Jan</option>
        <option value="feb">Feb</option>
        <option value="mar">Mar</option>
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
            {{-- <form action="#">
              <select class="form-control wmin-100">
                <option value="jan">Jan</option>
                <option value="feb">Feb</option>
                <option value="mar">Mar</option>
              </select>
            </form> --}}
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
            {{-- <form action="#">
              <select class="form-control wmin-100">
                <option value="jan">Jan</option>
                <option value="feb">Feb</option>
                <option value="mar">Mar</option>
              </select>
            </form> --}}
            <button type="button" class="btn bg-blue btn-icon legitRipple ml-3"><i class="icon-sync"></i></button>
            <button type="button" class="btn bg-pink-400 btn-icon ml-3 legitRipple" data-toggle="modal"
              data-target="#insert-fcr"><i class="icon-pencil7"></i></button>
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
            {{-- <form action="#">
              <select class="form-control wmin-100">
                <option value="jan">Jan</option>
                <option value="feb">Feb</option>
                <option value="mar">Mar</option>
              </select>
            </form> --}}
            <button type="button" class="btn bg-blue btn-icon legitRipple ml-3"><i class="icon-sync"></i></button>
            <button type="button" class="btn bg-pink-400 btn-icon ml-3 legitRipple" data-toggle="modal"
              data-target="#insert-rasio-sales"><i class="icon-pencil7"></i></button>
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
            {{-- <form action="#">
              <select class="form-control wmin-100">
                <option value="jan">Jan</option>
                <option value="feb">Feb</option>
                <option value="mar">Mar</option>
              </select>
            </form> --}}
            <button type="button" class="btn bg-blue btn-icon legitRipple ml-3"><i class="icon-sync"></i></button>
            <button type="button" class="btn bg-pink-400 btn-icon ml-3 legitRipple" data-toggle="modal"
              data-target="#insert-ces"><i class="icon-pencil7"></i></button>
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
            {{-- <form action="#">
              <select class="form-control wmin-100">
                <option value="jan">Jan</option>
                <option value="feb">Feb</option>
                <option value="mar">Mar</option>
              </select>
            </form> --}}
            <button type="button" class="btn bg-blue btn-icon legitRipple ml-3"><i class="icon-sync"></i></button>
            <button type="button" class="btn bg-pink-400 btn-icon ml-3 legitRipple" data-toggle="modal"
              data-target="#insert-quality-layanan"><i class="icon-pencil7"></i></button>
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

{{-- <div class="card">
  <div class="card-header header-elements-inline">
    <h5 class="card-title">Service Level</h5>
    <div class="header-elements">
      <div class="list-icons">
        <a class="list-icons-item" data-action="reload"></a>
        <button type="button" class="btn btn-link p-0" data-toggle="modal" data-target="#insert-service-level"><i
            class="icon-pencil7"></i></button>
      </div>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-xs table-striped table-bordered">
      <thead>
        <tr>
          <th rowspan="2">Formulasi</th>
          <th colspan="31">Periode </th>
          <th rowspan="2">Total</th>
        </tr>
        <tr>
          <th>1</th>
          <th>2</th>
          <th>3</th>
          <th>4</th>
          <th>5</th>
          <th>6</th>
          <th>7</th>
          <th>8</th>
          <th>9</th>
          <th>10</th>
          <th>11</th>
          <th>12</th>
          <th>13</th>
          <th>14</th>
          <th>15</th>
          <th>16</th>
          <th>17</th>
          <th>18</th>
          <th>19</th>
          <th>20</th>
          <th>21</th>
          <th>22</th>
          <th>23</th>
          <th>24</th>
          <th>25</th>
          <th>26</th>
          <th>27</th>
          <th>28</th>
          <th>29</th>
          <th>30</th>
          <th>31</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>Eugene</td>
          <td>Kopyov</td>
          <td>@Kopyov</td>
        </tr>
        <tr>
          <td>2</td>
          <td>Victoria</td>
          <td>Baker</td>
          <td>@Vicky</td>
        </tr>
        <tr>
          <td>3</td>
          <td>James</td>
          <td>Alexander</td>
          <td>@Alex</td>
        </tr>
        <tr>
          <td>4</td>
          <td>Franklin</td>
          <td>Morrison</td>
          <td>@Frank</td>
        </tr>
        <tr>
          <td>5</td>
          <td>Winnie</td>
          <td>the Pooh</td>
          <td>@Winnie</td>
        </tr>
        <tr>
          <td>6</td>
          <td>Garry</td>
          <td>Smith</td>
          <td>@Garry</td>
        </tr>
        <tr>
          <td>7</td>
          <td>Ian</td>
          <td>Berg</td>
          <td>@Ian</td>
        </tr>
      </tbody>
    </table>
  </div>
</div> --}}

<!-- Horizontal form modal -->
<div id="insert-new-data" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Insert Data [ <?php echo date("d - M, Y"); ?> ]</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="#" class="form-horizontal">
        <div class="modal-body">
          <div class="form-group row">
            <label class="col-form-label col-sm-5">Parameter</label>
            <div class="col-sm-7">
              <select data-placeholder="Pilih Parameter" class="form-control form-control-select2" name="parameter" id="parameter" data-fouc required>
                <option></option>
                <option value="service_level">Service Level</option>
                <option value="fcr">FCR</option>
                <option value="rasio_sales">Rasio Sales</option>
                <option value="ces">CES (by customer)</option>
                <option value="quality_layanan">Quality Layanan</option>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-5">Item</label>
            <div class="col-sm-7">
              <select data-placeholder="Pilih Item" class="form-control form-control-select2" name="item" id="item" data-fouc required>
                <option></option>
                <option value="cof">COF</option>
                <option value="call">Call</option>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-5">Value</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" autocomplete="off" name="value_item" id="value_item">
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
          <button type="submit" class="btn bg-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /horizontal form modal -->
@endsection
