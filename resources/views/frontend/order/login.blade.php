<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Alpina</title>
    <!--global css starts-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <!--end of global css-->
    <!--page level css starts-->
    <link type="text/css" rel="stylesheet" href="{{asset('assets/vendors/iCheck/css/all.css')}}" />
    <link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/login.css') }}">
    <link rel="stylesheet" href=" {{ asset('assets/css/font-awesome.min.css') }}">
    <!--end of page level css-->

</head>
<body>
<div class="container">
    <!--Content Section Start -->
    <div class="row">
        <div class="box animation flipInX">
            <div class="box1">
            <img src="{{ asset('assets/img/logo_alpina.png') }}" alt="logo" class="img-responsive mar">
            <h3 class="text-primary">Area de Clientes </h3>
                <!-- Notifications -->
                <div id="notific">
                @include('notifications')
                </div>
                <form action="{{ route('login') }}" class="omb_loginForm"  autocomplete="off" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="back" id="back" value="{{ $url }}">
                    <div class="form-group {{ $errors->first('email', 'has-error') }}">
                       
                        <input type="email" class="form-control" name="email" placeholder="Email"
                               value="{!! old('email') !!}">
                    </div>
                    <span class="help-block">{{ $errors->first('email', ':message') }}</span>
                    <div class="form-group {{ $errors->first('password', 'has-error') }}">
                       
                        <input type="password" class="form-control" name="password" placeholder="Contraseña">
                    </div>
                    <span class="help-block">{{ $errors->first('password', ':message') }}</span>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"> Recordar Usuario
                        </label>

                    </div>
                    <input type="submit" class="btn btn-block btn-primary" value="Log In">
                    Aun sin cuenta? <a href="{{ route('register') }}"><strong> Registrarse</strong></a>
                </form>
                <br/>
             <!--<div class="text-center">
                    <p>--OR--</p>

                    <p>Login with</p>
                    <a href="{{ url('/facebook') }}" class="social"><i class=" fa fa-facebook"></i> Facebook</a>

                    <a href="{{ url('/google') }}" class="social text-danger"><i class=" fa fa-google-plus"></i> Google</a>

                    {{--<a href="{{ url('/twitter') }}" class="social"><i class=" fa fa-twitter"></i> Twitter</a>--}}

                    <a href="{{ url('/linkedin') }}" class="social"><i class=" fa fa-linkedin"></i> LinkedIn</a>
                </div>-->

            </div>
            <br>
        <div class="bg-light animation flipInX">
            <a href="{{ route('forgot-password') }}" id="forgot_pwd_title">Olvidaste tu clave?</a>
        </div>
        </div>
    </div>
    <!-- //Content Section End -->
</div>
<!--global js starts-->
<script type="text/javascript" src="{{ asset('assets/js/frontend/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/frontend/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/frontend/login_custom.js') }}"></script>
<!--global js end-->
</body>
</html>