@extends('layouts/default')

{{-- Page title --}}
@section('title')
Página No Encontrada
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css starts-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/tabbular.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/animate/animate.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/jquery.circliful.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/owl_carousel/css/owl.carousel.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/owl_carousel/css/owl.theme.css') }}">

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
                <div id="animate" class="row">
                    <div class="number">4</div>
                    <div class="icon"> <i class="livicon" data-name="pacman" data-size="105" data-c="#f6c500" data-hc="#f1b21d" data-eventtype="click" data-iteration="15"></i>
                    </div>
                    <div class="number">4</div>
                </div>
                <div class="hgroup">
                    <h1>Página No Encontrada</h1>
                    <h2>Al parecer la página que busca no se encontró.</h2>
                    <a href="{{ route('home') }}">
                        <button type="button" class="btn btn-primary button-alignment">Regresar a Inicio</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop
{{-- footer scripts --}}
@section('footer_scripts')
    <!-- page level js starts-->
    <script src="{{ asset('assets/js/jquery-1.11.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <!--livicons-->
    <script src="{{ asset('assets/js/raphael-min.js') }}"></script>
    <script src="{{ asset('assets/js/livicons-1.4.min.js') }}"></script>
    <!-- end of global js -->
    <!-- begining of page level js-->
    <script src="{{ asset('assets/js/frontend/404.js') }}"></script>
    <!--page level js ends-->
@stop