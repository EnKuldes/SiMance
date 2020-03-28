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
            <li class="list-inline-item"><span class="badge badge-danger font-size-lg">80%</span></li>
          </ul>
        </div>
      </div>
      <div class="card-body pl-2 pt-2 pr-2 pb-0">
        <div class="row">
          <div class="col-md-4">
            <div class="svg-center" id="segmented_gauge" style="border-bottom:1px solid #999;"></div>
            <h4 class="text-center">Achv after bobot 68%</h4>
            <div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
              <div>
                <ul class="list list-unstyled mb-0">
                  <li><span class="font-weight-semibold">Target KPI</span></li>
                  <li>Service Level</li>
                  <li>FCR</li>
                  <li>Rasio Sales</li>
                  <li>CES</li>
                  <li>Quality</li>
                </ul>
              </div>

              <div class="text-sm-right mb-0 mt-3 mt-sm-0 ml-auto">
                <ul class="list list-unstyled mb-0">
                  <li><span class="font-weight-semibold">Param</span></li>
                  <li>>=95%</li>
                  <li>>=90%</li>
                  <li>>=15%</li>
                  <li>>=90% Puas</li>
                  <li><=5% NOK</li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <div class="table-responsive">
              <table class="table text-nowrap table-xs table-borderless">
                <thead>
                  <tr>
                    <td class="font-weight-bold font-size-lg w-100">KPI</td>
                    <td class="font-weight-bold font-size-lg text-right">Achv</td>
                    <td class="font-weight-bold font-size-lg text-right">Perf</td>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="font-weight-semibold">Service Level</td>
                    <td class="font-weight-semibold text-right">43%</td>
                    <td class="font-weight-semibold text-right">43%</td>
                  </tr>
                  <tr>
                    <td class="font-weight-semibold">FCR</td>
                    <td class="font-weight-semibold text-right">73%</td>
                    <td class="font-weight-semibold text-right">73%</td>
                  </tr>
                  <tr>
                    <td class="font-weight-semibold">Rasio Sales</td>
                    <td class="font-weight-semibold text-right">79%</td>
                    <td class="font-weight-semibold text-right">79%</td>
                 </tr>
                  <tr>
                    <td class="font-weight-semibold">CES (by Customer)</td>
                    <td class="font-weight-semibold text-right">89%</td>
                    <td class="font-weight-semibold text-right">89%</td>
                  </tr>
                  <tr>
                    <td class="font-weight-semibold">Quality Layanan</td>
                    <td class="font-weight-semibold text-right">89%</td>
                    <td class="font-weight-semibold text-right">89%</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="ml-3" style="border-top:1px solid #666;">
              <div class="col-md-12 mb-2">
                <div class="d-flex">
                  <h3 class="font-weight-semibold mb-0">YTD</h3>
                  <span class="badge bg-danger-800 badge-pill align-self-center ml-auto">sum/avg</span>
                </div>
              </div>
            </div>
            <div class="container-fluid">
              <div id="chart_bar_basic"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card border-left-3 border-left-success rounded-left-10">
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
@endsection
