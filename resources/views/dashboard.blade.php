@extends('layouts/app')

@section('title', 'Dashboard')

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
<div class="row">
  <div class="col-md-9">

    <!-- Daily sales -->
    <div class="card">
      <div class="card-header header-elements-inline">
        <h5 class="card-title font-weight-bold">CC 147</h5>
        <div class="header-elements">
          <span class="font-weight-bold font-size-lg text-info-600 ml-2">TOTAL BOBOT | 123.234 / {{ round($t_bobot ?? 0) }}%</span>
        </div>
      </div>

      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <legend class="font-weight-bold font-size-lg"><i class="icon-law mr-2"></i> Target & Bobot</legend>
            <ul class="list-unstyled mb-0">
              <li class="mb-3">
                <div class="d-flex align-items-center mb-1">Service Level <span class="text-muted ml-auto">Target
                    {{ round($target_service_level  ?? 0) }}% | Bobot {{ round($bobot_service_level  ?? 0) }}%</span>
                </div>
                <div class="progress" style="height: 1rem;">
                  <div class="progress-bar bg-info" style="width: <?=$target_service_level ?? 0?>%">
                    <span>{{ round($target_service_level  ?? 0) }}% Complete</span>
                  </div>
                </div>
              </li>

              <li class="mb-3">
                <div class="d-flex align-items-center mb-1">FCR <span class="text-muted ml-auto">Target
                    {{ round($target_fcr ?? 0 ) }}% | Bobot {{ round($bobot_fcr ?? 0 ) }}%</span></div>
                <div class="progress" style="height: 1rem;">
                  <div class="progress-bar bg-info" style="width: <?=$target_fcr ?? 0 ?>%">
                    <span>{{ round($target_fcr ?? 0 ) }}% Complete</span>
                  </div>
                </div>
              </li>

              <li class="mb-3">
                <div class="d-flex align-items-center mb-1">Rasio Sales <span class="text-muted ml-auto">Target
                    {{ round($target_rasio_sales ?? 0 ) }}% | Bobot {{ round($bobot_rasio_sales ?? 0 ) }}%</span></div>
                <div class="progress" style="height: 1rem;">
                  <div class="progress-bar bg-info" style="width: <?=$target_rasio_sales ?? 0 ?>%">
                    <span>{{ round($target_rasio_sales ?? 0 ) }}% Complete</span>
                  </div>
                </div>
              </li>

              <li class="mb-3">
                <div class="d-flex align-items-center mb-1">CES (by customer) <span class="text-muted ml-auto">Target
                    {{ round($target_ces ?? 0 ) }}% | Bobot {{ round($bobot_ces ?? 0 ) }}%</span></div>
                <div class="progress" style="height: 1rem;">
                  <div class="progress-bar bg-info" style="width: <?=$target_ces ?? 0 ?>%">
                    <span>{{ round($target_ces ?? 0 ) }}% Complete</span>
                  </div>
                </div>
              </li>

              <li>
                <div class="d-flex align-items-center mb-1">Quality Layanan <span class="text-muted ml-auto">Target
                    {{ round($target_quality_layanan ?? 0 ) }}% | Bobot
                    {{ round($bobot_quality_layanan ?? 0 ) }}%</span></div>
                <div class="progress" style="height: 1rem;">
                  <div class="progress-bar bg-info" style="width: <?=$target_quality_layanan ?? 0 ?>%">
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
                    <td align="center" class="font-weight-bold">Achv</td>
                    <td align="center" class="font-weight-bold">Perf</td>
                    <td align="center" class="font-weight-bold">Bobot</td>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <div>
                          <a href="#" class="text-default font-weight-semibold letter-icon-title">Service Level</a>
                          <div class="text-muted font-size-sm"><i class="icon-cog font-size-sm mr-1"></i> Updated Today
                          </div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0 text-success">
                        {{ isset($service_level) ? optional($service_level)->realisasi : 0 }}%</p>
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
                        <p class="font-weight-semibold text-orange font-size-lg text-center mb-0">232</p>
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
                          <div class="text-muted font-size-sm"><i class="icon-watch2 font-size-sm mr-1"></i> Updated Today
                          </div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0 text-success">
                        {{ isset($fcr) ? optional($fcr)->realisasi : 0 }}%</p>
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
                        <p class="font-weight-semibold text-orange font-size-lg text-center mb-0">232</p>
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
                          <div class="text-muted font-size-sm"><i class="icon-clipboard5 font-size-sm mr-1"></i> Updated Today
                          </div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0 text-success">
                        {{ isset($rasio_sales) ? optional($rasio_sales)->realisasi : 0 }}%</p>
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
                        <p class="font-weight-semibold text-orange font-size-lg text-center mb-0">232</p>
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
                          <a href="#" class="text-default font-weight-semibold letter-icon-title">CES ( by Customer
                            )</a>
                          <div class="text-muted font-size-sm"><i class="icon-search4 font-size-sm mr-1"></i> Updated Today
                          </div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0 text-success">
                        {{ isset($ces) ? optional($ces)->realisasi : 0 }}%</p>
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
                        <p class="font-weight-semibold text-orange font-size-lg text-center mb-0">232</p>
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
                          <div class="text-muted font-size-sm"><i class="icon-thumbs-up2 font-size-sm mr-2"></i> Updated Today
                          </div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0 text-success">
                        {{ isset($quality_layanan) ? optional($quality_layanan)->realisasi : 0 }}%</p>
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
                        <p class="font-weight-semibold text-orange font-size-lg text-center mb-0">232</p>
                    </td>
                    <td>
                      <p class="font-weight-bold font-size-lg text-center mb-0">
                        {{ isset($quality_layanan) ? optional($quality_layanan)->persetasi_bobot : 0 }}%</p>
                        <p class="font-weight-semibold text-violet font-size-lg text-center mb-0">232</p>
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
        <h5 class="card-title">Last Month Comparation</h5>
        <div class="header-elements">
          <div class="list-icons">
            <a class="list-icons-item" data-action="reload"></a>

          </div>
        </div>
      </div>

      <div class="card-body">
        <div class="chart-container">
          <div class="chart has-fixed-height" id="columns_basic1"></div>
        </div>
      </div>
    </div>
    <!-- /basic columns -->
  </div>
</div>
@endsection
