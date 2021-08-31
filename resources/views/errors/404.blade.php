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
{{-- breadcrumb --}}
@section('top')
    <div class="breadcum">
        <div class="container">
            <ol class="breadcrumb">
                <li class="hidden-xs">
                    <a href="{{ secure_url('/') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#3d3d3d" data-hc="#3d3d3d"></i>Inicio
                    </a>
                </li>
                <li class="hidden-md hidden-lg">
                    <a href="{{ secure_url('/') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#3d3d3d" data-hc="#3d3d3d"></i>
                    </a>
                </li>
                <li>
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#01bc8c" data-hc="#01bc8c"></i>
                    <a href="#">404</a>
                </li>
            </ol>
            
        </div>
    </div>
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
                    </a>
                </div>
                <div class="row">
                <div class="col-md-12 col-sm-12 text-center hidden-xs">
                <a href="{{ secure_url('/') }}"><img src="{{ secure_url('/').'/assets/images/404-alpina.jpg' }}" title="404 Página no encontrada" alt="404 Página no encontrada" class="img-responsive"></a>
                </div>
                <div class="col-md-12 col-sm-12 text-center visible-xs">
                <a href="{{ secure_url('/') }}"><img src="{{ secure_url('/').'/assets/images/404-alpinam.jpg' }}" title="404 Página no encontrada" alt="404 Página no encontrada" class="img-responsive"></a>
                </div>
            </div>
            </div>
        </div>
    </div>
@stop
{{-- footer scripts --}}
@section('footer_scripts')
   
@stop