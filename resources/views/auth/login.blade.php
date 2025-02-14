
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Login - PMII Dipayuda Banjarnegara</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport"/>
   <link rel="icon" href="{{ asset('assets/img/logo.png') }}" type="image/x-icon" />
   
    <link rel="stylesheet" href="{{ asset('assets/font/bootstrap-icons.min.css') }}">

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/auth/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/auth/css/plugins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/auth/css/kaiadmin.min.css') }}">
  </head>
  <body class="login bg-light">
    <div class="wrapper wrapper-login shadow-sm">
      <div class="container container-login animated fadeIn">
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <h3 class="text-center">Sign In</h3>
        <div class="login-form">
          <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-sub">
              <div class="form-floating form-floating-custom mb-3">
                <input
                  id="email"
                  name="email"
                  type="text"
                  class="form-control @error('email') is-invalid @enderror"
                  placeholder="Email"
                  value="{{ old('email') }}"
                />
                @error('email')
                <small id="emailHelp" class="form-text text-danger text-muted">{{ $message }}</small>
                @enderror
                <label for="email">Email</label>
                
              </div>
              <div class="form-floating form-floating-custom mb-3">
                <input
                  id="password"
                  name="password"
                  type="password"
                  class="form-control @error('email') is-invalid @enderror"
                  placeholder="password"
                />
                <label for="password">Password</label>
                <div class="show-password">
                  <i class="bi bi-eye"></i>
                </div>
                @error('password')
                <small id="passwordHelp" class="form-text text-danger text-muted">{{ $message }}</small>
                @enderror
              </div>
            </div>
            <div class="form-action mb-3">
              <button type="submit" class="btn btn-primary w-100 btn-login">Sign In</button>
            </div>
          </form>
        
        </div>
      </div>

    </div>
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>

    <script src="{{ asset('assets/auth/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/auth/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/auth/js/kaiadmin.min.js') }}"></script>
  </body>
</html>
