
@extends('layouts/default')

{{-- Page title --}}
@section('title')
PQR
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
                    <a href="#">Formulario PQR</a>
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
            @if (session('aviso'))
            <div class="alert alert-success">
                {{ session('aviso') }}
            </div>
             @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div style="padding:10px 30px; 20px 30px">
                <h3 class="text-center">Formulario PQR</h3>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form class="contact_us_form row" action="{{ secure_url('/pqr')}}" method="post" id="contactForm" enctype="multipart/form-data" novalidate="novalidate">
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <div class="form-group col-lg-4">
                    <input required="true" type="text" class="form-control" id="nombre_pqr" name="nombre_pqr" placeholder="Nombre*" value="{!! old('nombre_pqr') !!}">
                        {!! $errors->first('nombre_pqr', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group col-lg-4">
                    <input required="true" type="text" class="form-control" id="apellido_pqr" name="apellido_pqr" placeholder="Apellido*" value="{!! old('apellido_pqr') !!}">
                        {!! $errors->first('apellido_pqr', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group col-lg-4">
                        <select id="tdocume_pqr" name="tdocume_pqr" class="form-control">
                            <option value="">Seleccione Tipo de Documento</option>     
                            @foreach($t_documento as $tdoc)
                                <option value="{{ $tdoc->abrev_tipo_documento }}" {{ (old("tdocume_pqr") == $tdoc->abrev_tipo_documento ? "selected":"") }}>{{ $tdoc->abrev_tipo_documento}} - {{ $tdoc->nombre_tipo_documento}}</option>
                            @endforeach
                        </select>
                    {!! $errors->first('tdocume_pqr', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group col-lg-4">
                    <input required="true" type="text" class="form-control" id="identificacion_pqr" name="identificacion_pqr" placeholder="Identificación" value="{!! old('identificacion_pqr') !!}">
                        {!! $errors->first('identificacion_pqr', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group col-lg-4">
                    <input required="true" type="text" class="form-control" id="celular_pqr" name="celular_pqr" placeholder="Celular o Teléfono" value="{!! old('celular_pqr') !!}">
                        {!! $errors->first('celular_pqr', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group col-lg-4">
                    <input required="true" type="email_pqr" class="form-control" id="email_pqr" name="email_pqr" placeholder="Email" value="{!! old('email_pqr') !!}">
                        {!! $errors->first('email_pqr', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group col-lg-4">
                        <select id="pais_pqr" name="pais_pqr" class="form-control">
                            <option value="">Seleccione Pais</option>     
                            <option value="Colombia" @if (old('pais_pqr') == "Colombia") {{ 'selected' }} @endif>Colombia</option>  
                            <option value="Otro" @if (old('pais_pqr') == "Otro") {{ 'selected' }} @endif>Otro</option>  
                        </select>
                    {!! $errors->first('pais_pqr', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group col-lg-4">
                    <input required="true" type="ciudad_pqr" class="form-control" id="ciudad_pqr" name="ciudad_pqr" placeholder="Ciudad" value="{!! old('ciudad_pqr') !!}">
                        {!! $errors->first('ciudad_pqr', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group col-lg-4">
                        <select id="tipo_pqr" name="tipo_pqr" class="form-control">
                            <option value="">Seleccione Tipo de Contacto</option>     
                            <option value="Felicitaciones"  @if (old('tipo_pqr') == "Felicitaciones") {{ 'selected' }} @endif>Felicitaciones</option>  
                            <option value="Sugerencia"  @if (old('tipo_pqr') == "Sugerencia") {{ 'selected' }} @endif>Sugerencia</option> 
                            <option value="Reclamo o Queja"  @if (old('tipo_pqr') == "Reclamo o Queja") {{ 'selected' }} @endif>Reclamo o Queja</option>  
                            <option value="Otros"  @if (old('tipo_pqr') == "Otros") {{ 'selected' }} @endif>Otros</option>   
                        </select>
                    {!! $errors->first('tipo_pqr', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group col-lg-12">
                    <textarea required="true" class="form-control" name="mensaje_pqr" id="mensaje_pqr" rows="5" placeholder="Escriba su mensaje">{!! old('mensaje_pqr') !!}</textarea>
                        {!! $errors->first('mensaje_pqr', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group col-lg-12">
                    <input type="file" accept="*" name="file_update"  id="file_update"> <!-- rename it -->
                    {!! $errors->first('file_update', '<span class="help-block">:message</span> ') !!}
                </div>
                <div class="form-group col-lg-12">
                    <p>Adjuntar Archivo (.png, .jpg, .docx, .pdf): Peso Máximo 5MB</p>
                    <input type="hidden" name="_recaptcha" id="_recaptcha">
                        {!! $errors->first('g-recaptcha-response', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group col-lg-12">
                    <label style="font-size:12px;">
                        <input type="checkbox" name="habeas_cliente" id="habeas_cliente" value="1" require>  Acepto los <a href="{{ secure_url('paginas/terminos-condiciones')}}" class="menu-item" target="_blank" alt="Términos y Condiciones de Acceso a Alpina Go" title="Términos y Condiciones de Acceso a Alpina Go">Términos y Condiciones de Acceso a Alpina Go</a> y la  <a href="{{ secure_asset('uploads/files/politica_de_tratamiento_de_la_informacion.PDF') }}" class="menu-item" target="_blank" title="Políticas de Tratamiento de la Información" alt="Políticas de Tratamiento de la Información">Políticas de Tratamiento de la Información</a> de ALPINA PRODUCTOS ALIMENTICIOS S.A.
                    </label>
                    {!! $errors->first('habeas_cliente', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group col-lg-12">
                    <button style="height: 4em;" type="submit" value="submit" class="btn btn-block btn-primary">Enviar</button>
                </div>
            </form>
            <br>
            <br>
            <div class="resp"></div>
        </div>
    </div>
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