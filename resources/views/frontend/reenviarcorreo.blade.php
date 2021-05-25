
@extends('layouts/default')

{{-- Page title --}}
@section('title')
Gracias por confirmar Su Correo
@parent
@stop

<meta property="og:title" content="PQR Alpina GO!">
<meta property="og:description" content="PQR Alpina GO!">
<meta property="og:revisit-after" content="3 days">

<meta name="robots" content="index, follow">
<meta name="og:robots" content="index, follow">


{{-- page level styles --}}
@section('header_styles')

    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/shopping.css') }}">

    <link href="{{ secure_asset('assets/vendors/animate/animate.min.css') }}" rel="stylesheet" type="text/css"/>
    <style>
    .help-block {
        color: #a94442 !important;
    }
    </style>
@stop

{{-- Page content --}}
@section('content')

{{-- breadcrumb --}}
@section('top')
    <div class="breadcum">
        <div class="container">
            <ol class="breadcrumb">
                <li class="hidden-xs">
                    <a href="{{ secure_url('/') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#241F48" data-hc="#241F48"></i>Inicio
                    </a>
                </li>
                <li class="hidden-md hidden-lg">
                    <a href="{{ secure_url('/') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#241F48" data-hc="#241F48"></i>
                    </a>
                </li>
                <li>
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#241F48" data-hc="#241F48"></i>
                    <a href="#">Correo de confirmacion reeenviado</a>
                </li>
            </ol>
            
        </div>
    </div>
@stop



{{-- Page content --}}
@section('content')
<div class="container">
<br />
    
    <div class="row">
        <div class="col-md-12">

           <div style="padding:10px 30px; 20px 30px;color:#241F48;">
                <h1 class="text-center">El correo de confirmación ha sido reenviado, siga las instrucciones para confirmar su correo.</h1>
                <div class="separador"></div>
            </div>

            <a class="btn btn-primary" href="{{secure_url('clientes')}}">Ir al area de clientes </a>
          
          

        </div>
    </div>
    
    <br />
    <br />
    <br />
</div>
@endsection

{{-- page level scripts --}}
@section('footer_scripts')



     <script src="https://www.google.com/recaptcha/api.js?render=6LflWnsaAAAAAERsguImH7gK43wG2vehWYLSw63W"></script>

          <script>
            grecaptcha.ready(function() {
            grecaptcha.execute('6LflWnsaAAAAAERsguImH7gK43wG2vehWYLSw63W', {action: 'contactForm'})
            .then(function(token) {

            var recaptchaResponse = document.getElementById('_recaptcha');
            recaptchaResponse.value = token;
            });});





        </script>

@stop