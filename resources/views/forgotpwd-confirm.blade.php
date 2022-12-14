<!DOCTYPE html>
<html>
<head>
    {{--<meta charset="utf-8">--}}
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Olvidó su Contraseña | Alpina Go!</title>
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
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/forgot.css') }}">
    <!--end of page level css-->
</head>
<body>
<div class="container">
    <div class="row">
        <div class="box animation flipInX">
        <img src="{{ secure_asset('assets/img/login.png') }}" alt="Alpina GO!">
            <h3 class="text-primary">Reestrablecer Contraseña</h3>
            <p>Ingrese su Nueva Contraseña</p>
            <div id="notific">
            @include('notifications')
            </div>
            <form action="{{ secure_url('olvido-clave',compact(['userId','passwordResetCode'])) }}" class="omb_loginForm pwd_validation"  autocomplete="off" method="POST">
                {!! Form::token() !!}

                <label class="sr-only"> Nueva Contraseña</label>

                <input type="password" class="form-control" name="password" placeholder="Nueva Contraseña">
                <span class="help-block">{{ $errors->first('password', ':message') }}</span>

                <label class="sr-only">Confirme Nueva Contraseña</label>
                <input type="password" class="form-control mt-15" name="password_confirm" placeholder="Confirme Nueva Contraseña">
                
                <span class="help-block">{{ $errors->first('password_confirm', ':message') }}</span>
                <input type="submit" class="btn btn-block btn-primary" value="Reestablecer Contraseña" style="margin-top:10px;">
            </form>
        </div>
    </div>
</div>
<!--global js starts-->
<script type="text/javascript" src="{{ secure_asset('assets/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ secure_asset('assets/js/bootstrap.min.js') }}"></script>
<!--global js end-->
</body>
</html>
