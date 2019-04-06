<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro | AlpinaGo!</title>
    <!--global css starts-->
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/bootstrap.min.css') }}">
    <link rel="shortcut icon" href="{{ secure_asset('assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="icon" href="{{ secure_asset('assets/images/favicon.png') }}" type="image/x-icon">
    <!--end of global css-->
    <!--page level css starts-->
    <link type="text/css" rel="stylesheet" href="{{secure_asset('assets/vendors/iCheck/css/all.css')}}" />
    <link href="{{ secure_asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/register.css') }}">
    <!--end of page level css-->
</head>
<body>
<div class="container">
    <!--Content Section Start -->
    <div class="row">
        <div class="box animation flipInX">
            <img src="{{ secure_asset('assets/img/login.png') }}" alt="AlpinaGO!">
            
            <form action="{{ secure_url('signupembajador') }}" method="POST" id="reg_form">

                <h3> {{ $mensaje }}.</h3>
                
                <p> <a href="{{ secure_url('/') }}"> Inicio</a></p> 
              

            </form>
        </div>
    </div>
    <!-- //Content Section End -->
</div>
<!--global js starts-->
<script type="text/javascript" src="{{ secure_asset('assets/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ secure_asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ secure_asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
<script type="text/javascript" src="{{ secure_asset('assets/js/frontend/register_custom.js') }}"></script>
<!--global js end-->
</body>
</html>