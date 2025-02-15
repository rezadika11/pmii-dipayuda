
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title') - PMII Dipayuda Banjarnegara</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link rel="icon" href="{{ asset('assets/img/logo.png') }}" type="image/x-icon" />

    <!-- Fonts and icons -->
    <link rel="stylesheet" href="{{ asset('assets/font/bootstrap-icons.min.css') }}">

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/toastr/toastr.min.css') }}">

    @stack('css')
  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
    @include('backend.layouts.sidebar')
      <!-- End Sidebar -->

      <div class="main-panel">
      @include('backend.layouts.navbar')

        @yield('content')

       @include('backend.layouts.footer')
      </div>

    </div>
    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
     
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

    <!-- Kaiadmin JS -->
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
   
     <!-- JS Toastr -->
    <script src="{{ asset('assets/js/plugin/toastr/toastr.min.js') }}"></script>
    <!-- File konfigurasi toastr -->
    <script src="{{ asset('assets/js/toastr-config.js') }}"></script>
    
    @stack('js')
  </body>
</html>
