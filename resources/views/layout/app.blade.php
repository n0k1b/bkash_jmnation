<!DOCTYPE html>
<html lang="en">


<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>SN PLAY</title>
  <!-- plugins:css -->

  <link rel="stylesheet" href="{{ asset('assets/melody') }}/vendors/iconfonts/font-awesome/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('assets/melody') }}/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="{{ asset('assets/melody') }}/vendors/css/vendor.bundle.addons.css">
  <link rel="stylesheet" href="https://unpkg.com/izitoast/dist/css/iziToast.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel='stylesheet'>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('assets/melody') }}/css/style.css">
  <style>
      /* The switch - the box around the slider */
        .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
        }

        /* Hide default HTML checkbox */
        .switch input {
        opacity: 0;
        width: 0;
        height: 0;
        }

        /* The slider */
        .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
        }

        .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
        }

        input:checked + .slider {
        background-color: #2196F3;
        }

        input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
        border-radius: 34px;
        }

        .slider.round:before {
        border-radius: 50%;
        }
  </style>
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row default-layout-navbar">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="index-2.html"><img src="{{asset('assets/melody')}}/images/logos.svg" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="index-2.html"><img src="{{asset('assets/melody')}}/images/logos-mini.svg" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="fas fa-bars"></span>
        </button>
        <ul class="navbar-nav">
        </ul>
        <ul class="navbar-nav navbar-nav-right">

          <li class="nav-item d-lg-flex">
            <a class="nav-link" href="{{ route('logout') }}">
              <span class="btn btn-primary">Logout</span>
            </a>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="fas fa-bars"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      <div class="theme-setting-wrapper">
        <div id="settings-trigger"><i class="fas fa-fill-drip"></i></div>
        <div id="theme-settings" class="settings-panel">
          <i class="settings-close fa fa-times"></i>
          <p class="settings-heading">SIDEBAR SKINS</p>
          <div class="sidebar-bg-options selected" id="sidebar-light-theme"><div class="img-ss rounded-circle bg-light border mr-3"></div>Light</div>
          <div class="sidebar-bg-options" id="sidebar-dark-theme"><div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark</div>
          <p class="settings-heading mt-2">HEADER SKINS</p>
          <div class="color-tiles mx-0 px-4">
            <div class="tiles primary"></div>
            <div class="tiles success"></div>
            <div class="tiles warning"></div>
            <div class="tiles danger"></div>
            <div class="tiles info"></div>
            <div class="tiles dark"></div>
            <div class="tiles default"></div>
          </div>
        </div>
      </div>
      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item nav-profile">
            <div class="nav-link">
              <div class="profile-name">
                <p class="name">
                  Wallet: {{ auth()->user()->wallet }}
                </p>
                <p class="designation">

                </p>
              </div>
            </div>
          </li>

          @if(auth()->user()->role != 'admin')
          <li class="nav-item">
            <a class="nav-link" href="{{url('/')}}">
              <i class="fa fa-puzzle-piece menu-icon"></i>
              <span class="menu-title">Transaction</span>
            </a>
          </li>
          @endif

          @if(auth()->user()->role == 'admin')
          <li class="nav-item">
            <a class="nav-link" href="{{url('/')}}">
              <i class="fa fa-puzzle-piece menu-icon"></i>
              <span class="menu-title">User</span>
            </a>
          </li>
          @endif

          <li class="nav-item">
            <a class="nav-link" href="{{url('report')}}">
              <i class="fa fa-home menu-icon"></i>
              <span class="menu-title">Report</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{url('wallet_request')}}">
              <i class="fa fa-home menu-icon"></i>
              <span class="menu-title">Wallet Request</span>
            </a>
          </li>





        </ul>
      </nav>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
            @yield('main-panel')
        </div>
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="{{ asset('assets/melody') }}/vendors/js/vendor.bundle.base.js"></script>
  <script src="{{ asset('assets/melody') }}/vendors/js/vendor.bundle.addons.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="{{ asset('assets/melody') }}/js/off-canvas.js"></script>
  <script src="{{ asset('assets/melody') }}/js/hoverable-collapse.js"></script>
  <script src="{{ asset('assets/melody') }}/js/misc.js"></script>
  <script src="{{ asset('assets/melody') }}/js/settings.js"></script>
  <script src="{{ asset('assets/melody') }}/js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="{{ asset('assets/melody') }}/js/dashboard.js"></script>
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <!-- <script src="{{ asset('js/app.js') }}" ></script> -->


  <!-- End custom js for this page-->
  <!-- Custom js for this page-->
  @yield('js')
  <!-- End custom js for this page-->
</body>


</html>
