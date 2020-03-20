<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

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
  <script src="{{ asset('assets/js/plugins/notifications/pnotify.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/buttons/spin.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/buttons/ladda.min.js') }}"></script>
  <!-- /core JS files -->

  <!-- Theme JS files -->
  @yield('liblary')

  <script src="{{ asset('assets/js/app.js') }}"></script>
  <script src="{{ asset('assets/js/demo_pages/extra_pnotify.js') }}"></script>
  <script src="{{ asset('assets/js/demo_pages/components_buttons.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/ui/moment/moment.min.js') }}"></script>
  <script src="{{ asset('assets/js/demo_pages/extension_blockui.js') }}"></script>
  @yield('extra-liblary')
  <!-- /theme JS files -->
</head>

<body @yield('sidebar')>

  <div class="navbar navbar-sm navbar-expand-xl navbar-dark navbar-component navbar-sm bg-info-600 mb-0">
    <div class="text-left d-xl-none w-100">
      <button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse"
        data-target="#navbar-demo1-mobile">
        <img src="assets/images/logo.png" class="rounded-circle mr-2" height="34" alt="">
      </button>
    </div>

    <div class="navbar-collapse collapse" id="navbar-demo1-mobile">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a href="/dashboard" class="navbar-nav-link">
            <i class="icon-meter-fast mr-2"></i>
            Dashboard
          </a>
        </li>

        @if (Auth::user()->layanan == 1)
        <li class="nav-item">
          <a href="/cc-147" class="navbar-nav-link">
            <i class="icon-headset mr-2"></i>
            Daily CC 147
          </a>
        </li>

        @elseif (Auth::user()->layanan == 2)
        <li class="nav-item">
          <a href="/digital-media" class="navbar-nav-link">
            <i class="icon-presentation mr-2"></i>
            Daily Digital Media
          </a>
        </li>

        @elseif (Auth::user()->layanan == 3)
        <li class="nav-item">
          <a href="/c4" class="navbar-nav-link">
            <i class="icon-cogs mr-2"></i>
            Daily C4
          </a>
        </li>

        @elseif (Auth::user()->layanan == 4)
        <li class="nav-item">
          <a href="/myindihome" class="navbar-nav-link">
            <i class="icon-home5 mr-2"></i>
            Daily myIndiHome
          </a>
        </li>

        @endif

      </ul>

      <div class="navbar-collapse collapse" id="navbar-form-select2">
        <form class="mb-3 mb-xl-0 ml-xl-auto" action="#">
          <div class="wmin-xl-250">
            <div class="row">
              <div class="col-md-6">
                <select class="form-control form-control-select2 bg-transparent" data-placeholder="This Yeaar"
                  data-container-css-class="text-black" data-fouc id="list_year" style="width:100%">
                  {{--
                  <option></option>
                  <option value="2020">2020</option>
                   --}}
                </select>
              </div>

              <div class="col-md-6">
                <select class="form-control form-control-select2 bg-transparent" data-placeholder="This Month"
                  data-container-css-class="text-black" data-fouc id="list_month" style="width:100%">
                  {{--
                  <option></option>
                  <option value="jan">January</option>
                  <option value="feb">February</option>
                  <option value="mar">March</option>
                  <option value="apr">April</option>
                  <option value="mei">Mei</option>
                  <option value="jun">June</option>
                  <option value="jul">July</option>
                  <option value="aug">August</option>
                  <option value="sep">Sepember</option>
                  <option value="okt">Oktober</option>
                  <option value="nov">November</option>
                  <option value="des">Desember</option>
                  --}}
                </select>
              </div>
            </div>
          </div>
        </form>
      </div>

      <ul class="navbar-nav ml-xl-3">
        <li class="nav-item dropdown dropdown-user">
          <a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
            <img src="{{ asset('assets/images/image.png') }}" class="rounded-circle" alt="">
            <span>{{ Auth::user()->name }}</span>
          </a>

          <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
              <i class="icon-switch2"></i> {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </div>
        </li>
      </ul>
    </div>
  </div>
  <!-- /dark navbar demo -->

  <!-- Page content -->
  <div class="page-content">
    <!-- Main content -->
    <div class="content-wrapper">
      <!-- Content area -->
      <div class="content">
        {{-- <button type="button" class="btn btn-primary" id="block-page">Block the whole page</button> --}}
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
  <script type="text/javascript">
    function notificationScript(type, title, text) {
      new PNotify({
          title: title,
          text: text,
          delay: 300,
          addclass: 'alert alert-styled-left alert-arrow-left',
          type: type
      });
    }
    function chain3() {
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
         type:"post",
         url:'/list-date',
         //data: {'id_parameter':id},
         success: function(data){
          var ahtml = ''//'<option></option>';
          for (var i = 0; i < data.length; i++) {
            ahtml+="<option value='"+data[i]['year']+"'>"+data[i]['year']+"</option>"
            tempYear = data[i]['year'];
          }
          $('#list_year').html(ahtml);
        },
        error : function(data) {

          console.log("error chain2");

        }
      }).done(function(){
        $('#list_year').val(tempYear).trigger('change');
        //console.log(tempYear)

      });
    }
    function chain4(id) {
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
         type:"post",
         url:'/list-date',
         data: {'select_year':id},
         success: function(data){
          var ahtml = ''//'<option></option>';
          for (var i = 0; i < data.length; i++) {
            var d = new Date(id, data[i]['month']-1, 1);
            ahtml+="<option value='"+data[i]['month']+"'>"+moment(d).format('MMMM')+"</option>"
            tempMonth = data[i]['month'];
          }
          $('#list_month').html(ahtml);

        },
        error : function(data) {

          console.log("error chain2");

        }
      }).done(function(){
        $('#list_month').val(tempMonth).trigger('change');
        //console.log(tempMonth)

      });
    }
    $("#list_year").change(function() {
      var id = $(this).val();
      tempYear = id;
      if (id != "" && id != null)
      {
        chain4(id);
      }
    });
  </script>
</body>

</html>
