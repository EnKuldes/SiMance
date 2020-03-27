@extends('layouts/app')

@section('title', 'Daily All Site')

@section('liblary')
<script src="assets/js/plugins/visualization/echarts/echarts.min.js"></script>
<script src="assets/js/plugins/forms/styling/uniform.min.js"></script>
<script src="assets/js/plugins/forms/selects/select2.min.js"></script>
<script src="assets/js/plugins/extensions/jquery_ui/interactions.min.js"></script>
<script src="assets/js/plugins/visualization/d3/d3.min.js"></script>
<script src="assets/js/plugins/visualization/d3/d3_tooltip.js"></script>
@endsection

@section('extra-liblary')
<script src="assets/js/demo_pages/form_select2.js"></script>
<script src="assets/js/demo_pages/form_layouts.js"></script>
<script src="assets/js/demo_pages/widgets_stats.js"></script>
@endsection

@section('script')
@endsection

@section('content')
<div class="row">
  <div class="col-lg-6">
    <div class="card border-left-3 border-left-danger rounded-left-0">
      <div class="card-header bg-white header-elements-inline p-2">
        <h4 class="card-title font-weight-semibold">Layanan CC147</h4>
        <div class="header-elements">
          <ul class="list-inline list-inline-dotted mb-0">
            <li class="list-inline-item"><span class="font-size-lg font-weight-bold">Total Perf</span></li>
            <li class="list-inline-item"><span class="badge badge-danger font-size-lg">Badge</span></li>
          </ul>
        </div>
      </div>
      <div class="card-body pl-2 pt-2 pr-2 pb-0">
        <div class="row">
          <div class="col-md-4">
            <div class="svg-center" id="segmented_gauge"></div>
            <div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
              <div>
                <h3 class="font-weight-semibold text-center">Percentage Bobot</h3>
                <ul class="list list-unstyled mb-0">
                  <li>Invoice #: &nbsp;0028</li>
                  <li>Issued on: <span class="font-weight-semibold">2015/01/25</span></li>
                </ul>
              </div>

              <div class="text-sm-right mb-0 mt-3 mt-sm-0 ml-auto">
                <h3 class="font-weight-semibold">68%</h3>
                <ul class="list list-unstyled mb-0">
                  <li>Method: <span class="font-weight-semibold">SWIFT</span></li>
                  <li class="dropdown">
                    Status: &nbsp;
                    <a href="#" class="badge bg-danger-400 align-top dropdown-toggle" data-toggle="dropdown"
                      aria-expanded="false">Overdue</a>
                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end"
                      style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(112px, 19px, 0px);">
                      <a href="#" class="dropdown-item active"><i class="icon-alert"></i> Overdue</a>
                      <a href="#" class="dropdown-item"><i class="icon-alarm"></i> Pending</a>
                      <a href="#" class="dropdown-item"><i class="icon-checkmark3"></i> Paid</a>
                      <div class="dropdown-divider"></div>
                      <a href="#" class="dropdown-item"><i class="icon-spinner2 spinner"></i> On hold</a>
                      <a href="#" class="dropdown-item"><i class="icon-cross2"></i> Canceled</a>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <div class="d-flex">
              <h3 class="font-weight-semibold mb-0">3,450</h3>
              <span class="badge bg-success-400 align-self-center ml-auto font-size-lg">24234</span>
            </div>

            <div>
              Members online
              <div class="text-muted font-size-sm">489 avg</div>
            </div>
          </div>
        </div>
      </div>

      <div class="card-footer d-sm-flex justify-content-sm-between align-items-sm-center">
        <span>
          <span class="badge badge-mark border-danger mr-2"></span>
          Due:
          <span class="font-weight-semibold">2015/02/25</span>
        </span>

        <ul class="list-inline list-inline-condensed mb-0 mt-2 mt-sm-0">
          <li class="list-inline-item">
            <a href="#" class="text-default"><i class="icon-eye8"></i></a>
          </li>
          <li class="list-inline-item dropdown">
            <a href="#" class="text-default dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>

            <div class="dropdown-menu dropdown-menu-right">
              <a href="#" class="dropdown-item"><i class="icon-printer"></i> Print invoice</a>
              <a href="#" class="dropdown-item"><i class="icon-file-download"></i> Download invoice</a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item"><i class="icon-file-plus"></i> Edit invoice</a>
              <a href="#" class="dropdown-item"><i class="icon-cross2"></i> Remove invoice</a>
            </div>
          </li>
        </ul>
      </div>

      <div class="card-footer d-sm-flex justify-content-sm-between align-items-sm-center pl-0 pt-1 pr-0 pb-0">
        <div class="container-fluid">
          <div id="chart_bar_basic"></div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card border-right-3 border-right-success rounded-right-10">
      <div class="card-body">
        <div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
          <div>
            <h6 class="font-weight-semibold">Rebecca Manes</h6>
            <ul class="list list-unstyled mb-0">
              <li>Invoice #: &nbsp;0027</li>
              <li>Issued on: <span class="font-weight-semibold">2015/02/24</span></li>
            </ul>
          </div>

          <div class="text-sm-right mb-0 mt-3 mt-sm-0 ml-auto">
            <h6 class="font-weight-semibold">$5,100</h6>
            <ul class="list list-unstyled mb-0">
              <li>Method: <span class="font-weight-semibold">Paypal</span></li>
              <li class="dropdown">
                Status: &nbsp;
                <a href="#" class="badge bg-success-400 align-top dropdown-toggle" data-toggle="dropdown">Paid</a>
                <div class="dropdown-menu dropdown-menu-right">
                  <a href="#" class="dropdown-item"><i class="icon-alert"></i> Overdue</a>
                  <a href="#" class="dropdown-item"><i class="icon-alarm"></i> Pending</a>
                  <a href="#" class="dropdown-item active"><i class="icon-checkmark3"></i> Paid</a>
                  <div class="dropdown-divider"></div>
                  <a href="#" class="dropdown-item"><i class="icon-spinner2 spinner"></i> On hold</a>
                  <a href="#" class="dropdown-item"><i class="icon-cross2"></i> Canceled</a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="card-footer d-sm-flex justify-content-sm-between align-items-sm-center">
        <span>
          <span class="badge badge-mark border-success mr-2"></span>
          Due:
          <span class="font-weight-semibold">2015/03/24</span>
        </span>

        <ul class="list-inline list-inline-condensed mb-0 mt-2 mt-sm-0">
          <li class="list-inline-item">
            <a href="#" class="text-default"><i class="icon-eye8"></i></a>
          </li>
          <li class="list-inline-item dropdown">
            <a href="#" class="text-default dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>

            <div class="dropdown-menu dropdown-menu-right">
              <a href="#" class="dropdown-item"><i class="icon-printer"></i> Print invoice</a>
              <a href="#" class="dropdown-item"><i class="icon-file-download"></i> Download invoice</a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item"><i class="icon-file-plus"></i> Edit invoice</a>
              <a href="#" class="dropdown-item"><i class="icon-cross2"></i> Remove invoice</a>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<!-- Widgets with charts -->
<div class="row">
  <div class="col-sm-6 col-xl-3">

    <!-- Basic area chart -->
    <div class="card">
      <div class="card-body">
        <div class="d-flex">
          <h3 class="font-weight-semibold mb-0">$18,390</h3>
          <div class="list-icons ml-auto">
            <a class="list-icons-item" data-action="reload"></a>
          </div>
        </div>

        <div>
          Today's revenue
          <div class="text-muted font-size-sm">$37,578 avg</div>
        </div>
      </div>

      <div id="chart_area_basic"></div>
    </div>
    <!-- /basic area chart -->

  </div>

  <div class="col-sm-6 col-xl-3">

    <!-- Basic bar chart -->
    <div class="card">
      <div class="card-body">
      </div>
    </div>
    <!-- /basic bar chart -->

  </div>

  <div class="col-sm-6 col-xl-3">

    <!-- Basic line chart -->
    <div class="card">
      <div class="card-body">
        <div class="d-flex">
          <h3 class="font-weight-semibold mb-0">4,389</h3>
          <div class="list-icons ml-auto">
            <a class="list-icons-item" data-action="reload"></a>
          </div>
        </div>

        <div>
          Orders weekly
          <div class="text-muted font-size-sm">4,728 avg</div>
        </div>
      </div>

      <div id="line_chart_simple"></div>
    </div>
    <!-- /basic line chart -->

  </div>

  <div class="col-sm-6 col-xl-3">

    <!-- Basic sparklines -->
    <div class="card">
      <div class="card-body">
        <div class="d-flex">
          <h3 class="font-weight-semibold mb-0">49.4%</h3>
          <div class="list-icons ml-auto">
            <div class="dropdown">
              <a href="#" class="list-icons-item dropdown-toggle" data-toggle="dropdown"><i class="icon-cog3"></i></a>
              <div class="dropdown-menu dropdown-menu-right">
                <a href="#" class="dropdown-item"><i class="icon-sync"></i> Update data</a>
                <a href="#" class="dropdown-item"><i class="icon-list-unordered"></i> Detailed log</a>
                <a href="#" class="dropdown-item"><i class="icon-pie5"></i> Statistics</a>
                <a href="#" class="dropdown-item"><i class="icon-cross3"></i> Clear list</a>
              </div>
            </div>
          </div>
        </div>

        <div>
          Current server load
          <div class="text-muted font-size-sm">34.6% avg</div>
        </div>
      </div>

      <div id="sparklines_basic"></div>
    </div>
    <!-- /basic sparklines -->

  </div>
</div>

<div class="row">
  <div class="col-sm-6 col-xl-3">

    <!-- Area chart in colored card -->
    <div class="card bg-indigo-400 has-bg-image">
      <div class="card-body">
        <div class="d-flex">
          <h3 class="font-weight-semibold mb-0">$18,390</h3>
          <div class="list-icons ml-auto">
            <a class="list-icons-item" data-action="reload"></a>
          </div>
        </div>

        <div>
          Today's revenue
          <div class="font-size-sm opacity-75">$37,578 avg</div>
        </div>
      </div>

      <div id="chart_area_color"></div>
    </div>
    <!-- /area chart in colored card -->

  </div>

  <div class="col-sm-6 col-xl-3">

    <!-- Bar chart in colored card -->
    <div class="card bg-danger-400 has-bg-image">
      <div class="card-body">
        <div class="d-flex">
          <h3 class="font-weight-semibold mb-0">3,450</h3>
          <span class="badge bg-danger-800 badge-pill align-self-center ml-auto">+53,6%</span>
        </div>

        <div>
          Members online
          <div class="font-size-sm opacity-75">489 avg</div>
        </div>
      </div>

      <div class="container-fluid">
        <div id="chart_bar_color"></div>
      </div>
    </div>
    <!-- /bar chart in colored card -->

  </div>

  <div class="col-sm-6 col-xl-3">

    <!-- Line chart in colored card -->
    <div class="card bg-blue-400 has-bg-image">
      <div class="card-body">
        <div class="d-flex">
          <h3 class="font-weight-semibold mb-0">4,389</h3>
          <div class="list-icons ml-auto">
            <a class="list-icons-item" data-action="reload"></a>
          </div>
        </div>

        <div>
          Orders weekly
          <div class="font-size-sm opacity-75">4,728 avg</div>
        </div>
      </div>

      <div id="line_chart_color"></div>
    </div>
    <!-- /line chart in colored card -->

  </div>

  <div class="col-sm-6 col-xl-3">

    <!-- Sparklines in colored card -->
    <div class="card bg-success-400 has-bg-image">
      <div class="card-body">
        <div class="d-flex">
          <h3 class="font-weight-semibold mb-0">49.4%</h3>
          <div class="list-icons ml-auto">
            <div class="dropdown">
              <a href="#" class="list-icons-item dropdown-toggle" data-toggle="dropdown"><i class="icon-cog3"></i></a>
              <div class="dropdown-menu dropdown-menu-right">
                <a href="#" class="dropdown-item"><i class="icon-sync"></i> Update data</a>
                <a href="#" class="dropdown-item"><i class="icon-list-unordered"></i> Detailed log</a>
                <a href="#" class="dropdown-item"><i class="icon-pie5"></i> Statistics</a>
                <a href="#" class="dropdown-item"><i class="icon-cross3"></i> Clear list</a>
              </div>
            </div>
          </div>
        </div>

        <div>
          Current server load
          <div class="font-size-sm opacity-75">34.6% avg</div>
        </div>
      </div>

      <div id="sparklines_color"></div>
    </div>
    <!-- /sparklines in colored card -->

  </div>
</div>
<!-- /widgets with charts -->
@endsection
