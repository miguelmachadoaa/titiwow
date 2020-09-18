<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

     <link rel="canonical" href="{{secure_url('login')}}" />


    <title>Login | Alpina Go!</title>
    <!--global css starts-->
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/bootstrap.min.css') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ secure_asset('assets/img/favicon/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ secure_asset('assets/img/favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ secure_asset('assets/img/favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="{{ secure_asset('assets/img/favicon/site.webmanifest')}}">
    <link rel="mask-icon" href="{{ secure_asset('assets/img/favicon/safari-pinned-tab.svg')}}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <!--end of global css-->
    <!--page level css starts-->
    <link type="text/css" rel="stylesheet" href="{{secure_asset('assets/vendors/iCheck/css/all.css')}}" />
    <link href="{{ secure_asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/login.css') }}">
    <link rel="stylesheet" href=" {{ secure_asset('assets/css/font-awesome.min.css') }}">
    <!--end of page level css-->
    @if (App::environment('production')) 

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-129370910-1"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-129370910-1');
        </script>
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-PG9RTMH');</script>
        <!-- End Google Tag Manager -->
    @endif
</head>
<body>
@if (App::environment('production')) 
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PG9RTMH"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
    @endif
<div class="container">
    <!--Content Section Start -->
    <div class="row">
        <div class="box animation flipInX">
            <div class="box1 text-center">
            <a href="{{ secure_url('/') }}"><img src="{{ secure_asset('assets/img/login.png') }}" alt="Alpina GO!"></a>
            <h3 class="text-primary">Mi Perfil</h3>
                <!-- Notifications -->
                <div id="notific">
                @include('notifications')
                </div>
                <form action="{{ secure_url('login') }}" class="omb_loginForm"  autocomplete="off" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="back" id="back" value="0">
                    <div class="form-group {{ $errors->first('email', 'has-error') }}">
                        <label class="sr-only">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Email"
                               value="{!! old('email') !!}">
                    </div>
                    <span class="help-block">{{ $errors->first('email', ':message') }}</span>
                    <div class="form-group {{ $errors->first('password', 'has-error') }}">
                        <label class="sr-only">Contraseña</label>
                        <input type="password" class="form-control" name="password" placeholder="Contraseña">
                    </div>
                    <span class="help-block">{{ $errors->first('password', ':message') }}</span>
                    <!--div class="checkbox">
                        <label>
                            <input type="checkbox"> Recordar Usuario
                        </label>

                    </div-->
                    <div class="form-group">
                        <div class="col-md-6">
                            <input type="submit" class="btn btn-block btn-primary" value="Iniciar Sesión">
                        </div>
                        <div class="col-md-6">
                            <a class="btn btn-block btn-danger" href="{{ secure_url('/') }}">Regresar a Inicio</a>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    ¿Aún sin Cuenta? <a href="{{ secure_url('registro') }}"><strong> Registrarse</strong>
                </form>
                <!--div class="text-center">
                    <p>--OR--</p>
                    <p>Login with</p>
                    <a href="{{ secure_url('/facebook') }}" class="social"><i class=" fa fa-facebook"></i> Facebook</a>

                    <a href="{{ secure_url('/google') }}" class="social text-danger"><i class=" fa fa-google-plus"></i> Google</a>

                    {{--<a href="{{ secure_url('/twitter') }}" class="social"><i class=" fa fa-twitter"></i> Twitter</a>--}}

                    <a href="{{ secure_url('/linkedin') }}" class="social"><i class=" fa fa-linkedin"></i> LinkedIn</a>
                </div-->

            </div>
            <br>
        <div class="bg-light animation flipInX">
            <a href="{{ secure_url('olvido-clave') }}" id="forgot_pwd_title">¿Olvidó su Contraseña?</a>
        </div>
        </div>
    </div>
    <!-- //Content Section End -->
</div>
<!--global js starts-->
<script type="text/javascript" src="{{ secure_asset('assets/js/frontend/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ secure_asset('assets/js/frontend/bootstrap.min.js') }}"></script>
<script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ secure_asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
<script type="text/javascript" src="{{ secure_asset('assets/js/frontend/login_custom.js') }}"></script>
<!--global js end-->
</body>
</html>
