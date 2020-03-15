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

    <ul class="navbar-nav flex-wrap mr-2">
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

<div class="row">
  <div class="col-md-9">

    <!-- Daily sales -->
    <div class="card">
      <div class="card-header header-elements-inline">
        <h5 class="card-title font-weight-bold">CC 147</h5>
        <div class="header-elements">
          <span class="font-weight-bold font-size-lg text-info-600 ml-2">TOTAL BOBOT | {{ round($t_bobot) }} %</span>
        </div>
      </div>

      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <legend class="font-weight-bold font-size-lg"><i class="icon-law mr-2"></i> Target & Bobot</legend>
            <ul class="list-unstyled mb-0">
              <li class="mb-3">
                <div class="d-flex align-items-center mb-1">Service Level <span class="text-muted ml-auto">Target {{ round($target_service_level) }}% | Bobot {{ round($bobot_service_level) }}%</span></div>
                <div class="progress" style="height: 1rem;">
                  <div class="progress-bar bg-info" style="width: <?=$target_service_level?>%">
                    <span>{{ round($target_service_level) }}% Complete</span>
                  </div>
                </div>
              </li>

              <li class="mb-3">
                <div class="d-flex align-items-center mb-1">FCR <span class="text-muted ml-auto">Target {{ round($target_fcr) }}% | Bobot {{ round($bobot_fcr) }}%</span></div>
                <div class="progress" style="height: 1rem;">
                  <div class="progress-bar bg-orange" style="width: <?=$target_fcr?>%">
                    <span>{{ round($target_fcr) }}% Complete</span>
                  </div>
                </div>
              </li>

              <li class="mb-3">
                <div class="d-flex align-items-center mb-1">Rasio Sales <span class="text-muted ml-auto">Target {{ round($target_rasio_sales) }}% | Bobot {{ round($bobot_rasio_sales) }}%</span></div>
                <div class="progress" style="height: 1rem;">
                  <div class="progress-bar bg-success" style="width: <?=$target_rasio_sales?>%">
                    <span>{{ round($target_rasio_sales) }}% Complete</span>
                  </div>
                </div>
              </li>

              <li class="mb-3">
                <div class="d-flex align-items-center mb-1">CES (by customer) <span class="text-muted ml-auto">Target {{ round($target_ces) }}% | Bobot {{ round($bobot_ces) }}%</span></div>
                <div class="progress" style="height: 1rem;">
                  <div class="progress-bar bg-pink" style="width: <?=$target_ces?>%">
                    <span>{{ round($target_ces) }}% Complete</span>
                  </div>
                </div>
              </li>

              <li>
                <div class="d-flex align-items-center mb-1">Quality Layanan <span class="text-muted ml-auto">Target {{ round($target_quality_layanan) }}% | Bobot {{ round($bobot_quality_layanan) }}%</span></div>
                <div class="progress" style="height: 1rem;">
                  <div class="progress-bar bg-primary" style="width: <?=$target_quality_layanan?>%">
                    <span>{{ round($target_quality_layanan) }}% Complete</span>
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
                    <th class="w-100">Parameter</th>
                    <th>Realisasi</th>
                    <th>Achievement</th>
                    <th>Bobot</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <div>
                          <a href="#" class="text-default font-weight-semibold letter-icon-title">Service Level</a>
                          <div class="text-muted font-size-sm"><i class="icon-checkmark3 font-size-sm mr-1"></i> New order
                          </div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="font-weight-semibold font-size-lg text-center mb-0">{{ optional($service_level)->realisasi }} %</p>
                    </td>
                    <td>
                      <p class="font-weight-semibold font-size-lg text-center mb-0">{{ optional($service_level)->achievement }} %</p>
                    </td>
                    <td>
                      <p class="font-weight-semibold font-size-lg text-center mb-0">{{ optional($service_level)->persetasi_bobot }} %</p>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <div>
                          <a href="#" class="text-default font-weight-semibold letter-icon-title">FCR</a>
                          <div class="text-muted font-size-sm"><i class="icon-spinner11 font-size-sm mr-1"></i> Renewal
                          </div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="font-weight-semibold font-size-lg text-center mb-0">{{ optional($fcr)->realisasi }} %</p>
                    </td>
                    <td>
                      <p class="font-weight-semibold font-size-lg text-center mb-0">{{ optional($fcr)->achievement }} %</p>
                    </td>
                    <td>
                      <p class="font-weight-semibold font-size-lg text-center mb-0">{{ optional($fcr)->persetasi_bobot }} %</p>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <div>
                          <a href="#" class="text-default font-weight-semibold letter-icon-title">Rasio Sales</a>
                          <div class="text-muted font-size-sm"><i class="icon-lifebuoy font-size-sm mr-1"></i> Support
                          </div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="font-weight-semibold font-size-lg text-center mb-0">{{ optional($rasio_sales)->realisasi }} %</p>
                    </td>
                    <td>
                      <p class="font-weight-semibold font-size-lg text-center mb-0">{{ optional($rasio_sales)->achievement }} %</p>
                    </td>
                    <td>
                      <p class="font-weight-semibold font-size-lg text-center mb-0">{{ optional($rasio_sales)->persetasi_bobot }} %</p>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <div>
                          <a href="#" class="text-default font-weight-semibold letter-icon-title">CES ( by Customer )</a>
                          <div class="text-muted font-size-sm"><i class="icon-lifebuoy font-size-sm mr-1"></i> Support
                          </div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="font-weight-semibold font-size-lg text-center mb-0">{{ optional($ces)->realisasi }} %</p>
                    </td>
                    <td>
                      <p class="font-weight-semibold font-size-lg text-center mb-0">{{ optional($ces)->achievement }} %</p>
                    </td>
                    <td>
                      <p class="font-weight-semibold font-size-lg text-center mb-0">{{ optional($ces)->persetasi_bobot }} %</p>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <div>
                          <a href="#" class="text-default font-weight-semibold letter-icon-title">Quality Layanan</a>
                          <div class="text-muted font-size-sm"><i class="icon-spinner11 font-size-sm mr-2"></i> Renewal
                          </div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="font-weight-semibold font-size-lg text-center mb-0">{{ optional($quality_layanan)->realisasi }} %</p>
                    </td>
                    <td>
                      <p class="font-weight-semibold font-size-lg text-center mb-0">{{ optional($quality_layanan)->achievement }} %</p>
                    </td>
                    <td>
                      <p class="font-weight-semibold font-size-lg text-center mb-0">{{ optional($ces)->persetasi_bobot }} %</p>
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
  <div class="col-md-3">asdf</div>
</div>
@endsection
