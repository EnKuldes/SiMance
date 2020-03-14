<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>@yield('title') | SiMance</title>

  <!-- Global stylesheets -->
  {{-- <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css"> --}}
  <link href="{{ asset('assets/images/favicon.ico') }}" rel="icon">
  <link href="{{ asset('assets/css/google-style.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('assets/css/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('assets/css/bootstrap_limitless.min.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('assets/css/layout.min.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('assets/css/components.min.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('assets/css/colors.min.css') }}" rel="stylesheet" type="text/css">
  <!-- /global stylesheets -->

  <!-- Core JS files -->
  <script src="{{ asset('assets/js/main/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/js/main/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/loaders/blockui.min.js') }}"></script>
  <!-- /core JS files -->

  <!-- Theme JS files -->
  @yield('liblary')

  <script src="{{ asset('assets/js/app.js') }}"></script>

  @yield('extra-liblary')
  <!-- /theme JS files -->
</head>

<body @yield('sidebar')>
  <!-- Main navbar -->
  <div class="navbar navbar-expand-md navbar-dark navbar-sm bg-teal-600">
    <div class="d-md-none">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
        <i class="icon-tree5"></i>
      </button>
    </div>
    <div class="collapse navbar-collapse" id="navbar-mobile">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a href="/dashboard"
            class="navbar-nav-link">
            <i class="icon-meter-fast mr-2"></i>
            Dashboard
          </a>
        </li>

        <li class="nav-item">
          <a href="/cc-147"
            class="navbar-nav-link">
            <i class="icon-headset mr-2"></i>
            CC 147
          </a>
        </li>

        <li class="nav-item">
          <a href="/digital-media"
            class="navbar-nav-link">
            <i class="icon-presentation mr-2"></i>
            Digital Media
          </a>
        </li>

        <li class="nav-item">
          <a href="/c4"
            class="navbar-nav-link">
            <i class="icon-cogs mr-2"></i>
            C4
          </a>
        </li>

        <li class="nav-item">
          <a href="/myindihome"
            class="navbar-nav-link">
            <i class="icon-home5 mr-2"></i>
            myIndiHome
          </a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown dropdown-user">
          <a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
            <img src="{{ asset('assets/images/image.png') }}" class="rounded-circle" alt="">
            <span>sdaf</span>
          </a>

          <div class="dropdown-menu dropdown-menu-right">
            <a href="/outlet-digital/agent/logout" class="dropdown-item"><i class="icon-switch2"></i> Logout</a>
          </div>
        </li>
      </ul>
    </div>
  </div>
  <!-- /main navbar -->

  <!-- Page content -->
  <div class="page-content">
    <!-- Main content -->
    <div class="content-wrapper">

      <!-- Content area -->
      <div class="content">
        @yield('content')
      </div>
      <!-- /content area -->

      <!-- Footer -->
      <div class="navbar navbar-expand-lg navbar-light">
        <div class="text-center d-lg-none w-100">
          <button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse"
            data-target="#navbar-footer">
            <i class="icon-unfold mr-2"></i>
            Footer
          </button>
        </div>

        <div class="navbar-collapse collapse" id="navbar-footer">
          <span class="navbar-text">
            &copy; 2020. <a href="#">SiMance 1.0 </a> by <a href="#">DevOps Infomedia</a>
          </span>

          <span class="navbar-text ml-xl-auto">
            <strong>System Integrated Performance</strong>
          </span>
        </div>
      </div>
      <!-- /footer -->

    </div>
    <!-- /main content -->
  </div>
  <!-- /page content -->
  @yield('script')
</body>

</html>
