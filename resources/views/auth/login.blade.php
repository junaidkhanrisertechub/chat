<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fevicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Start css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">    
    <!-- End css -->
</head>
<body class="vertical-layout">
    <!-- Start Containerbar -->
    <div id="containerbar" class="containerbar authenticate-bg">
        <!-- Start Container -->
        <div class="container">
            <div class="auth-box login-box">
                <!-- Start row -->
                <div class="row no-gutters align-items-center justify-content-center">                    
                    <!-- Start col -->
                    <div class="col-md-6 col-lg-5">
                        <div class="auth-box-left">
                            <div class="card">
                                <div class="card-body">
                                    <h4>Your comminuty awaits.</h4>
                                    <div class="auth-box-icon">
                                        <img src="assets/images/authentication/auth-box-icon.svg" class="img-fluid" alt="auth-box-icon">
                                    </div>
                                    <div class="auth-box-logo">
                                        <img src="assets/images/logo.svg" class="img-fluid " alt="logo">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Start end -->
                    <!-- Start col -->
                    <div class="col-md-6 col-lg-5">
                        <!-- Start Auth Box -->
                        <div class="auth-box-right">
                            <div class="card">
                                <div class="card-body">

                                    <form method="POST" action="{{ route('login') }}">
                                        
                                        <h4 class="text-primary mb-4">{{ __('Login') }} !</h4>

                                        @csrf

                                        <div class="form-group">
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-row mb-3">
                                            <div class="col-sm-6">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                                    <label class="custom-control-label font-14" for="remember">
                                                        {{ __('Remember Me') }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="forgot-psw"> 
                                                    @if (Route::has('password.request'))
                                                        <a class="font-14" href="{{ route('password.request') }}">
                                                            {{ __('Forgot Your Password?') }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-success btn-lg btn-block font-18">{{ __('Login') }}</button>

                                    </form>

                                    <div class="login-or">
                                        <h6 class="text-muted">OR</h6>
                                    </div>
                                    <div class="social-login text-center">
                                        <button type="submit" class="btn btn-primary-rgba btn-lg btn-block font-18"><i class="mdi mdi-facebook mr-2"></i>Log in with Facebook</button>
                                        <button type="submit" class="btn btn-danger-rgba btn-lg btn-block font-18"><i class="mdi mdi-google mr-2"></i>Log in with Google</button>
                                    </div>
                                    <!-- <p class="mb-0 mt-3">Don't have a account? <a href="{{ route('register') }}">Sign up</a></p> -->
                                </div>
                            </div>
                        </div>
                        <!-- End Auth Box -->
                    </div>
                    <!-- End col -->
                </div>
                <!-- End row -->
            </div>
        </div>
        <!-- End Container -->
    </div>
    <!-- End Containerbar -->
    <!-- Start js -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
    <script src="{{ asset('assets/js/detect.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.slimscroll.js') }}"></script>
    <!-- End js -->
</body>
</html>