@extends('layouts/default')

{{-- Page title --}}
@section('title')
Página No Encontrada
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css starts-->
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/tabbular.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/animate/animate.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/jquery.circliful.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/owl_carousel/css/owl.carousel.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/owl_carousel/css/owl.theme.css') }}">

    <!--end of page level css-->
@stop

{{-- content --}}
@section('content')
    <div class="container contain_body">
        <div class="row">
            <div class="col-md-3 hidden-xs">
            @include('layouts.sidebar')
            </div>
            <div class="col-md-9">
                <div class="hgroup">
                    <h1>Página No Encontrada</h1>
                    <h2>Al parecer la página que busca no se encontró.</h2>
                    <a href="{{ secure_url('/') }}">
                        <button type="button" class="btn btn-primary button-alignment">Regresar a Inicio</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop
{{-- footer scripts --}}
@section('footer_scripts')
    
@stop