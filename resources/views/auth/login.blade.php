<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>SiMance</title>

  <!-- Global stylesheets -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
  <link href="assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
  <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="assets/css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">
  <link href="assets/css/layout.min.css" rel="stylesheet" type="text/css">
  <link href="assets/css/components.min.css" rel="stylesheet" type="text/css">
  <link href="assets/css/colors.min.css" rel="stylesheet" type="text/css">
  <!-- /global stylesheets -->

  <!-- Core JS files -->
  <script src="assets/js/main/jquery.min.js"></script>
  <script src="assets/js/main/bootstrap.bundle.min.js"></script>
  <script src="assets/js/plugins/loaders/blockui.min.js"></script>
  <script src="assets/js/plugins/ui/ripple.min.js"></script>
  <!-- /core JS files -->

  <!-- Theme JS files -->
  <script src="assets/js/plugins/forms/styling/uniform.min.js"></script>

  <script src="assets/js/app.js"></script>
  <script src="assets/js/demo_pages/login.js"></script>
  <!-- /theme JS files -->

</head>

<body>

  <!-- Page content -->
  <div class="page-content login-cover">

    <!-- Main content -->
    <div class="content-wrapper">

      <!-- Content area -->
      <div class="content d-flex justify-content-center align-items-center">

        <!-- Login card -->
        <form class="login-form" method="POST" action="{{ route('login') }}">
            {{-- Input CSRF --}}
            @csrf

          <div class="card mb-0">
            <div class="card-body">
              <div class="text-center mb-3">
                <i
                  class="icon-stats-bars icon-2x text-warning-400 border-warning-400 border-3 rounded-round p-3 mb-3 mt-1"></i>
                <h5 class="mb-0">SiMance</h5>
                <span class="d-block text-muted">System Integration Performance</span>
              </div>

              <div class="form-group form-group-feedback form-group-feedback-left">
                <input type="text" class="form-control" placeholder="Username" name="username" id="username" autocomplete="off">
                
                {{-- Perlu div buat nampilin error, sementara ini begini saja dulu --}}
                @error('username')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-control-feedback">
                  <i class="icon-user text-muted"></i>
                </div>
              </div>

              <div class="form-group form-group-feedback form-group-feedback-left">
                <input type="password" class="form-control" placeholder="Password" name="password" id="password" autocomplete="off">

                {{-- Perlu div buat nampilin error, sementara ini begini saja dulu --}}
                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-control-feedback">
                  <i class="icon-lock2 text-muted"></i>
                </div>
              </div>


              <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Sign in <i
                    class="icon-circle-right2 ml-2"></i></button>
              </div>
            </div>
          </div>
        </form>
        <!-- /login card -->

      </div>
      <!-- /content area -->

    </div>
    <!-- /main content -->

  </div>
  <!-- /page content -->

</body>

</html>