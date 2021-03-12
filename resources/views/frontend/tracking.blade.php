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
        
        .bs-wizard {margin-top: 40px;}

        /*Form Wizard*/
        .bs-wizard {border-bottom: solid 1px #e0e0e0; padding: 0 0 10px 0;}
        .bs-wizard > .bs-wizard-step {padding: 0; position: relative;}
        .bs-wizard > .bs-wizard-step + .bs-wizard-step {}
        .bs-wizard > .bs-wizard-step .bs-wizard-stepnum {color: #595959; font-size: 16px; margin-bottom: 5px;}
        .bs-wizard > .bs-wizard-step .bs-wizard-info {color: #999; font-size: 14px;}
        .bs-wizard > .bs-wizard-step > .bs-wizard-dot {position: absolute; width: 30px; height: 30px; display: block; background: #90f38d; top: 45px; left: 50%; margin-top: -15px; margin-left: -15px; border-radius: 50%;} 
        .bs-wizard > .bs-wizard-step > .bs-wizard-dot:after {content: ' '; width: 14px; height: 14px; background: #3DC639; border-radius: 50px; position: absolute; top: 8px; left: 8px; } 
        .bs-wizard > .bs-wizard-step > .progress {position: relative; border-radius: 0px; height: 8px; box-shadow: none; margin: 20px 0;}
        .bs-wizard > .bs-wizard-step > .progress > .progress-bar {width:0px; box-shadow: none; background: #90f38d;}
        .bs-wizard > .bs-wizard-step.complete > .progress > .progress-bar {width:100%;}
        .bs-wizard > .bs-wizard-step.active > .progress > .progress-bar {width:50%;}
        .bs-wizard > .bs-wizard-step:first-child.active > .progress > .progress-bar {width:0%;}
        .bs-wizard > .bs-wizard-step:last-child.active > .progress > .progress-bar {width: 100%;}
        .bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot {background-color: #f4f4f4;}
        .bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot:after {opacity: 0;}
        .bs-wizard > .bs-wizard-step:first-child  > .progress {left: 50%; width: 50%;}
        .bs-wizard > .bs-wizard-step:last-child  > .progress {width: 50%;}
        .bs-wizard > .bs-wizard-step.disabled a.bs-wizard-dot{ pointer-events: none; }
        /*END Form Wizard*/
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
                <h4 class="text-center" style="color:#ffffff !important;">Orden: {{$orden->referencia}}</h4>
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
            <div style="min-height:150px;">
            <div class="row bs-wizard" style="border-bottom:0;">
                
                
                <div class="col-xs-3 bs-wizard-step @if($history_envio->estatus_envio=='4') active @elseif($history_envio->estatus_envio== '5' || $history_envio->estatus_envio == '6' || $history_envio->estatus_envio == '7') complete  @else disabled @endif"><!-- complete -->
                  <div class="text-center bs-wizard-stepnum">En Proceso</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                </div>
                
                <div class="col-xs-3 bs-wizard-step @if($history_envio->estatus_envio=='5') active @elseif($history_envio->estatus_envio== '6' || $history_envio->estatus_envio == '7') complete  @else disabled @endif"><!-- complete -->
                  <div class="text-center bs-wizard-stepnum">Asignado</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                </div>
                
                <div class="col-xs-3 bs-wizard-step @if($history_envio->estatus_envio=='6') active @elseif($history_envio->estatus_envio== '7') complete  @else disabled @endif"><!-- active -->
                  <div class="text-center bs-wizard-stepnum">En Ruta</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                </div>

                <div class="col-xs-3 bs-wizard-step @if($history_envio->estatus_envio=='7') active  @else disabled @endif"><!-- active -->
                  <div class="text-center bs-wizard-stepnum">Entregado</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>

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