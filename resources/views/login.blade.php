
<!DOCTYPE html>
<html lang="en">


<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>JMNation MFS</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('assets/melody') }}/vendors/iconfonts/font-awesome/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('assets/melody') }}/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="{{ asset('assets/melody') }}/vendors/css/vendor.bundle.addons.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('assets/melody') }}/css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth">
        <div class="row w-100">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left p-5">
              <div class="brand-logo" style="text-align: center;">
                <img src="images/logo.png" alt="logo">
              </div>
              <form class="pt-3" action="{{route('login')}}" method="POST">
                @csrf
                <div class="form-group">
                  <input type="email" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Email" name="email">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Password" name="password">
                </div>
                <div class="mt-3">
                  <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" type="submit">SIGN IN</button>
                </div>
                <div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" class="form-check-input">
                      Keep me signed in
                    </label>
                  </div>
                  <a href="#" class="auth-link text-black">Forgot password?</a>
                </div>

              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
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
  <!-- endinject -->
</body>


</html>
