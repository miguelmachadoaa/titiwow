<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Alpina Go!</title>
    <!--global css starts-->
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/bootstrap.min.css') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ secure_asset('assets/img/favicon/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ secure_asset('assets/img/favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ secure_asset('assets/img/favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="{{ secure_asset('assets/img/favicon/site.webmanifest')}}">
    <link rel="mask-icon" href="{{ secure_asset('assets/img/favicon/safari-pinned-tab.svg')}}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">    <!--end of global css-->
    <!--end of global css-->
    <!--page level css starts-->
    <link type="text/css" rel="stylesheet" href="{{secure_asset('assets/vendors/iCheck/css/all.css')}}" />
    <link href="{{ secure_asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/login.css') }}">
    <link rel="stylesheet" href=" {{ secure_asset('assets/css/font-awesome.min.css') }}">
    <!--end of page level css-->

</head>
<body>
<div class="container">
    <!--Content Section Start -->
    <div class="row">
        <div class="box animation flipInX">
            <div class="box1 text-center">
            <a href="{{ secure_url('/') }}"><img src="{{ secure_asset('assets/img/login.png') }}" alt="Alpina GO!"></a>
            <h3 class="text-primary">Mi Perfil</h3>
                <!-- Notifications -->
                <div id="notific">
                
                        <div class="alert alert-success alert-dismissable margin5">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <strong>El Registro ha sido deshabilitado temporalmente.</strong> 
                            
                        </div>


                </div>
                    
                    <div class="form-group">
                        
                        <div class="col-md-6">
                            <a class="btn btn-block btn-danger" href="{{ secure_url('/') }}">Regresar a Inicio</a>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                   
               

            </div>
            <br>
       
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
