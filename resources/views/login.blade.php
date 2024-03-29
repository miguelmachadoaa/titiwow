<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

     <link rel="canonical" href="{{secure_url('login')}}" />


    <title>Login | Titiwow</title>
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
</head>
<body>
<div class="container">
    <!--Content Section Start -->
    <div class="row">
        <div class="box animation flipInX">
            <div class="box1 text-center">
            <a href="{{ secure_url('/') }}"><img src="{{ secure_asset('assets/images/logo_go.png') }}" alt="Titiwow!"></a>
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
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                               value="{!! old('email') !!}">
                    </div>
                    <span class="help-block">{{ $errors->first('email', ':message') }}</span>
                    <div class="form-group {{ $errors->first('password', 'has-error') }}">
                        <label class="sr-only">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña">
                    </div>
                    <span class="help-block">{{ $errors->first('password', ':message') }}</span>
                   
                    <div class="form-group">
                        <div class="col-md-6">
                            <input type="submit" id="btnsubmit" class="btn btn-block btn-primary" value="Iniciar Sesión">
                        </div>
                        <div class="col-md-6">
                            <a class="btn btn-block btn-danger" href="{{ secure_url('/') }}">Regresar a Inicio</a>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <br />
                    ¿Aún sin Cuenta? <a href="{{ secure_url('registro') }}"><strong> Registrarse</strong>
                </form>
              

            </div>
            <br>
        <div class="bg-light animation flipInX">
            <a href="{{ secure_url('olvido-clave') }}" id="forgot_pwd_title">¿Olvidó su Contraseña?</a>
        </div>
        </div>
    </div>
    <!-- //Content Section End -->

    

    <input type="hidden" id="base" name="base" value="{{secure_url('/')}}">
</div>
<!--global js starts-->
<script type="text/javascript" src="{{ secure_asset('assets/js/frontend/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ secure_asset('assets/js/frontend/bootstrap.min.js') }}"></script>
<script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ secure_asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
<script type="text/javascript" src="{{ secure_asset('assets/js/frontend/login_custom.js') }}"></script>
<script src="https://www.google.com/recaptcha/api.js?render=6LflWnsaAAAAAERsguImH7gK43wG2vehWYLSw63W"></script>

<script>
 /* grecaptcha.ready(function() {
  grecaptcha.execute('6LflWnsaAAAAAERsguImH7gK43wG2vehWYLSw63W', {action: 'omb_loginForm'})
  .then(function(token) {

  var recaptchaResponse = document.getElementById('_recaptcha');
  recaptchaResponse.value = token;
  });});*/


  $("input#password").focus(function(){
        $(this).val('');
        $(this).get(0).type = 'password';
    });

    $("input#password").click(function(){
        $(this).val('');
        $(this).get(0).type = 'password';
    });

    $("input#password").keypress(function(){
        //$(this).val('');
        $(this).get(0).type = 'password';
    });



    $("#password").keypress(function(e) {
       if(e.which == 13) {

          e.preventDefault();

          $( "#btnsubmit" ).trigger( "click" );
       }
    });

    $("#email").keypress(function(e) {
       if(e.which == 13) {

           e.preventDefault();

          $( "#btnsubmit" ).trigger( "click" );
       }
    });

    


$(document).ready(function(){

    $('#btnsubmit').on('click', function(){

        base=$('#base').val();

        _token=$("[name='_token']").attr("value");

        email=btoa($('#email').val());
        password=btoa($('#password').val());
        
        $.ajax({
            url: base+'/login',
            data:{
                password:password,
                email:email,
                _token:_token
            },  
            type: "POST",
                success:function(data) {

               if(data=='false'){


                $('#notific').html('<div class="alert alert-danger">Usuario o Clave invalida por favor intente nuevamente.</div>');
                
               }else{
                $(location).attr('href',data);
               }
                    
            }
        });



    });



});



</script>
<!--global js end-->
</body>
</html>
