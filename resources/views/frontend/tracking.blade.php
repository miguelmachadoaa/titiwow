@extends('layouts/default')

{{-- Page title --}}
@section('title')
Tracking de Ordenes 
@parent
@stop


@section('meta_tags')
<meta property="og:title" content="Carrito de Compras | Alpina GO!">
<meta property="og:image" content="{{$configuracion->seo_image}}" />
<meta property="og:url" content="" />
<meta property="og:description" content="Carrito de Compras">

@if(isset($url))
<link rel="canonical" href="" />
@endif
@endsection

{{-- page level styles --}}
@section('header_styles')

 <link rel="canonical" href="{{secure_url('order/detail')}}" />


<!-- modal css -->

    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/shopping.css') }}">
    
    <link href="{{ secure_asset('assets/vendors/animate/animate.min.css') }}" rel="stylesheet" type="text/css"/>

    <link href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />

    <link href="{{ secure_asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" />

    <style type="text/css">
        #circulo {
            width: 10rem;
            height: 10rem;
            border-radius: 50%;
            background: #3DC639;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        #circulo > p {
            font-family: sans-serif;
            color: white;
            font-weight: bold;
        }
    </style>

@stop

{{-- breadcrumb --}}
@section('top')
    <div class="breadcum">
        <div class="container">
            <ol class="breadcrumb">
                <li>
                    <a href="{{ secure_url('/') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#3d3d3d" data-hc="#3d3d3d"></i>Inicio
                    </a>
                </li>
                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#3d3d3d" data-hc="#3d3d3d"></i>
                    <a href="#">Ordenes</a>
                </li>

                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#3d3d3d" data-hc="#3d3d3d"></i>
                    <a href="#}">Sigue tu Orden</a>
                </li>
            </ol>
           
        </div>
    </div>
@stop

{{-- Page content --}}
@section('content')

<div class="container">

    <div class="row">
        <div class="col-md-12">
            <div style="padding:10px 30px; 20px 30px">
                <h3 class="text-center">Sigue tu Orden</h3>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div style="padding:10px 30px; 20px 30px; background-color:#241F48;">
                <h4 class="text-center" style="color:#ffffff !important;">Orden: ALP10000</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div style="padding:10px 30px; 20px 30px; background-color:#f4f4f4;">
                <div class="row">
                    <div class="col-md-4">&nbsp;
                    </div>
                    <div class="col-md-4">
                        <h5 class="text-center">Ciudad: Bogotá</h5>
                    </div>
                    <div class="col-md-4">&nbsp;
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div style="border:1px solid #f4f4f4; min-height:300px;">
                <div class="row">
                    <div class="col-md-2 col-sm-offset-1">
                        <div style="padding:10px 30px; 20px 30px">
                            <div id="circulo" class="text-center">
                                <p>ok</p>
                            </div>
                            <h3 class="text-center">Aprobado</h3>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div style="padding:10px 30px; 20px 30px">
                            <h3 class="text-center">En Preparación</h3>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div style="padding:10px 30px; 20px 30px">
                            <h3 class="text-center">Asignado</h3>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div style="padding:10px 30px; 20px 30px">
                            <h3 class="text-center">En Ruta</h3>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div style="padding:10px 30px; 20px 30px">
                            <h3 class="text-center">Entregado</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div style="height:50px;">
                &nbsp;
            </div>
        </div>
    </div>
</div>
@endsection

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ secure_asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>

    <script language="javascript" type="text/javascript" src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}"></script>

    <script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>

    <script language="javascript">
   

    </script>


@stop


@section('footer_scripts')
    
@stop