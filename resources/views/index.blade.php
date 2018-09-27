@extends('layouts/default')

{{-- Page title --}}
@section('title')
Inicio
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

{{-- slider --}}
@section('top')
    <!--Carousel Start -->
    <div id="owl-demo" class="owl-carousel owl-theme">
        <div class="item"><img src="{{ asset('assets/images/slide_1.jpg') }}" alt="slider-image">
        </div>
        <div class="item"><img src="{{ asset('assets/images/slide_2.jpg') }}" alt="slider-image">
        </div>
        <div class="item"><img src="{{ asset('assets/images/slide_4.png') }}" alt="slider-image">
        </div>
    </div>
    <!-- //Carousel End -->
@stop

{{-- content --}}
@section('content')

   
    <!-- //Layout Section Start -->
    <!-- Seccion categoria Inicio -->
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 wow slideInLeft" data-wow-duration="1.5s">
                <div class="row">
                    @if(!$categorias->isEmpty())
                        @foreach($categorias as $categoria)
                            <div class="col-md-4 col-sm-12 col-xs-12"  id="caja_categoria">
                                <div class="{{ $categoria->css_categoria }}">
                                    <div class="text-align:center;" id="contenido_cat">
                                        <h2>{{ $categoria->nombre_categoria }}</h2>
                                        <a href="{{ route('categoria', [$categoria->slug]) }}">VER TODOS</a>                                
                                    </div>
                                </div>
                            </div>
                            @if ($loop->iteration % 3 == 0)
                                </div>
                                <div class="row">
                            @endif
                    @endforeach
                    @else
                    <div class="alert alert-danger">
                        <strong>Lo Sentimos!</strong> No Existen Categorias para Mostrar.
                    </div>
                @endif
                </div>

            </div>
        </div>
        <!-- //Seccion categoria Fin -->

    </div>
    <!-- //Container End -->
@stop
{{-- footer scripts --}}
@section('footer_scripts')
    <!-- page level js starts-->
    <script type="text/javascript" src="{{ asset('assets/js/frontend/jquery.circliful.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/wow/js/wow.min.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/owl_carousel/js/owl.carousel.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/frontend/carousel.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/frontend/index.js') }}"></script>
    <!--page level js ends-->
@stop
