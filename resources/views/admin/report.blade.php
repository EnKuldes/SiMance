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
@endsection

@section('script')
<script src="assets/js/demo_pages/form_select2.js"></script>
<script src="assets/js/demo_pages/form_layouts.js"></script>
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
            <h4 class="text-primary mb-2 mt-md-2">Invoice #49029</h4>
            <ul class="list list-unstyled mb-0">
              <li>Date: <span class="font-weight-semibold">January 12, 2015</span></li>
              <li>Due date: <span class="font-weight-semibold">May 12, 2015</span></li>
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
        <table class="table table-sm table-bordered">
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
            <tr>
              <td>
                <h6 class="mb-0">Create UI design model</h6>
                <span class="text-muted">One morning, when Gregor Samsa woke from troubled.</span>
              </td>
              <td>57</td>
              <td><span class="font-weight-semibold">$3,990</span></td>
              <td><span class="font-weight-semibold">$3,990</span></td>
            </tr>
            <tr>
              <td>
                <h6 class="mb-0">Support tickets list doesn't support commas</h6>
                <span class="text-muted">I'd have gone up to the boss and told him just what i think.</span>
              </td>
              <td>12</td>
              <td><span class="font-weight-semibold">$840</span></td>
              <td><span class="font-weight-semibold">$840</span></td>
            </tr>
            <tr>
              <td>
                <h6 class="mb-0">Fix website issues on mobile</h6>
                <span class="text-muted">I am so happy, my dear friend, so absorbed in the exquisite.</span>
              </td>
              <td>31</td>
              <td><span class="font-weight-semibold">$2,170</span></td>
              <td><span class="font-weight-semibold">$2,170</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="col-md-7">
      <div class="table-responsive">
        <table class="table table-sm table-bordered">
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
            <tr>
              <td>
                <h6 class="mb-0">Create UI design model</h6>
                <span class="text-muted">One morning, when Gregor Samsa woke from troubled.</span>
              </td>
              <td>57</td>
              <td><span class="font-weight-semibold">$3,990</span></td>
              <td><span class="font-weight-semibold">$3,990</span></td>
            </tr>
            <tr>
              <td>
                <h6 class="mb-0">Support tickets list doesn't support commas</h6>
                <span class="text-muted">I'd have gone up to the boss and told him just what i think.</span>
              </td>
              <td>12</td>
              <td><span class="font-weight-semibold">$3,990</span></td>
              <td><span class="font-weight-semibold">$3,990</span></td>
            </tr>
            <tr>
              <td>
                <h6 class="mb-0">Fix website issues on mobile</h6>
                <span class="text-muted">I am so happy, my dear friend, so absorbed in the exquisite.</span>
              </td>
              <td>31</td>
              <td><span class="font-weight-semibold">$2,170</span></td>
              <td><span class="font-weight-semibold">$3,990</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  @endforeach

  {{-- <div class="card-body">
    <div class="d-md-flex flex-md-wrap">

      <div class="pt-2 mb-3 wmin-md-300 ml-auto">
        <div class="pt-2 mb-3 pr-2">
          <h6 class="mb-3 text-center">Authorized person</h6>
          <div class="mb-3 text-center">
            <img src="assets/images/signature.png" width="150" alt="">
          </div>

          <ul class="list-unstyled text-muted">
            <li class="text-center">Eugene Kopyov</li>
          </ul>
        </div>
      </div>

      <div class="pt-2 mb-3 wmin-md-300 ml-auto">
        <div class="pt-2 mb-3 pr-2">
          <h6 class="mb-3 text-center">Authorized person</h6>
          <div class="mb-3 text-center">
            <img src="assets/images/signature.png" width="150" alt="">
          </div>

          <ul class="list-unstyled text-muted">
            <li class="text-center">Eugene Kopyov</li>
          </ul>
        </div>
      </div>


      <div class="pt-2 mb-3 wmin-md-300 ml-auto">
        <div class="pt-2 mb-3 pr-2">
          <h6 class="mb-3 text-center">Authorized person</h6>
          <div class="mb-3 text-center">
            <img src="assets/images/signature.png" width="150" alt="">
          </div>

          <ul class="list-unstyled text-muted">
            <li class="text-center">Eugene Kopyov</li>
          </ul>
        </div>
      </div>


      <div class="pt-2 mb-3 wmin-md-300 ml-auto">
        <div class="pt-2 mb-3 pr-2">
          <h6 class="mb-3 text-center">Authorized person</h6>
          <div class="mb-3 text-center">
            <img src="assets/images/signature.png" width="150" alt="">
          </div>

          <ul class="list-unstyled text-muted">
            <li class="text-center">Eugene Kopyov</li>
          </ul>
        </div>
      </div>

    </div>
  </div> --}}

  {{-- <div class="card-footer">
    <span class="text-muted">Thank you for using Limitless. This invoice can be paid via PayPal, Bank transfer, Skrill
      or Payoneer. Payment is due within 30 days from the date of delivery. Late payment is possible, but with with a
      fee of 10% per month. Company registered in England and Wales #6893003, registered office: 3 Goodman Street,
      London E1 8BF, United Kingdom. Phone number: 888-555-2311</span>
  </div> --}}
</div>
@endsection
