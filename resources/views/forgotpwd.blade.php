<!DOCTYPE html>
<html>
<head>
    {{--<meta charset="utf-8">--}}
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Olvidó su Contraseña | Alpina Go!</title>
    <!--global css starts-->
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/bootstrap.min.css') }}">
    <link href="{{ secure_asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
    <link rel="shortcut icon" href="{{ secure_asset('assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="icon" href="{{ secure_asset('assets/images/favicon.png') }}" type="image/x-icon">
    <!--end of global css-->
    <!--page level css starts-->
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/forgot.css') }}">
    <!--end of page level css-->
</head>
<body>
<div class="container">
    <div class="row">
        <div class="box animation flipInX">
            <img src="{{ secure_asset('assets/img/login.png') }}" alt="Alpina GO!">
            <h3 class="text-primary">Reestrablecer Contraseña</h3>
            <p>Ingrese su Email</p>
            <div id="notific">
            @include('notifications')
            </div>
            <form action="{{ secure_url('forgot-password') }}" class="omb_loginForm" autocomplete="off" method="POST">
                {!! Form::token() !!}
                <div class="form-group">
                    <label class="sr-only"></label>
                    <input type="email" class="form-control email" name="email" placeholder="Email"
                           value="{!! old('email') !!}">
                    <span class="help-block">{{ $errors->first('email', ':message') }}</span>
                </div>
                <div class="form-group">
                    <input class="form-control btn btn-primary btn-block" type="submit" value="Restablecer Contraseña">
                </div>
            </form>

            ¿Quiere regresar al Login?<a href="{{ route('login') }}"> Click Aquí</a>
        </div>
    </div>
</div>
<!--global js starts-->
<script type="text/javascript" src="{{ secure_asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ secure_asset('assets/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ secure_asset('assets/js/frontend/forgotpwd_custom.js') }}"></script>
<!--global js end-->
</body>
</html>
