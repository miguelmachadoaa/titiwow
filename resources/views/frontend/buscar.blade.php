
@extends('layouts/default')

{{-- Page title --}}
@section('title')
Resultado de la Búsqueda @parent
@stop

{{-- page level styles --}}

@section('meta_tags')


<link rel="canonical" href="{{$url}}" />

<meta property="og:title" content="{{ $configuracion->seo_titulo }} | Alpina GO!">
<meta property="og:description" content="{{ $configuracion->seo_descripcion }}">
<meta property="og:image" content="{{ $configuracion->seo_image }}" />
<meta property="og:url" content="{{$url}}" />
<meta name="description" content="{{$configuracion->seo_description}}"/>


@if(isset($configuracion->robots))

    @if($configuracion->robots==null)

    @else

        <meta name="robots" content="{{$configuracion->robots}}">
        
    @endif

@else

    <meta name="robots" content="index, follow">
    
@endif



@if(isset($configuracion->cuenta_twitter))
          <meta name="twitter:card" content="summary">
<meta name="twitter:site" content="{{'@'.$configuracion->cuenta_twitter}}">
<meta name="twitter:description" content="{{$configuracion->seo_description}}">
<meta name="twitter:title" content="{{ $configuracion->seo_title}}">
<meta name="twitter:image" content="{{$configuracion->seo_url}}">
@endif
@endsection

@section('header_styles')


    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/cart.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/shopping.css') }}">
    <link href="{{ secure_asset('assets/vendors/animate/animate.min.css') }}" rel="stylesheet" type="text/css"/>
@stop

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
                <li >
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#241F48" data-hc="#241F48"></i>
                    <a href="#">Busqueda</a>
                </li>
                <li >
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#241F48" data-hc="#241F48"></i>
                    <a href="#">{{ $termino }}</a>
                </li>
                
            </ol>
            
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container contain_body">
<div class="row">
<div class="col-md-3 hidden-xs hidden-sm" style="padding-right:30px">
@include('layouts.sidebar')
</div>
<div class="col-md-9">


    


    @if(isset($banner->id))


      <div class="row hidden-xs" >
            <div class="col-sm-12" style="margin-top:20px">
                <a target="_blank" href="{{$banner->enlace_categoria}}"><img style="width: 100%;"  src="{{secure_url('/assets/images/'.$banner->banner_categoria)}}" alt=""></a>
            </div>
        </div>

        <div class="row visible-xs" >
            <div class="col-sm-12" style="margin-top:20px">
               <a  target="_blank" href="{{$banner->enlace_categoria}}"><img  style="width: 100%;" src="{{secure_url('/assets/images/'.$banner->banner_movil_categoria)}}" alt=""></a> 
            </div>
        </div>



    @else


    @endif



    <div class="products">
        <div class="row">
        @if(count($productos)>0)

        @php $i=0; @endphp

            @foreach($prods as $producto)

               @if($producto->tipo_producto=='1' || $producto->tipo_producto=='3' || $producto->tipo_producto=='4')

                    @if($configuracion->mostrar_agotados=='1')
                    
                            @php $i++; @endphp

                            @include('frontend.producto')


                            @if ($i % 4 == 0)
                                </div>
                                <div class="row">
                            @endif

                    @else

                            @if(isset($inventario[$producto->id]))

                                @if($inventario[$producto->id]>0)

                                    @php $i++; @endphp

                                    @include('frontend.producto')


                                        @if ($i % 4 == 0)
                                            </div>
                                            <div class="row">
                                        @endif

                                @endif

                            @endif



                    @endif
                   
                @else <!-- Si es combo -->

                    @if(isset($combos[$producto->id]))

                        @if($combos[$producto->id])

                            @php $i++; @endphp

                            @include('frontend.producto')


                                @if ($i % 4 == 0)
                                    </div>
                                    <div class="row">
                                @endif

                        @endif

                    @endif

                @endif


            @endforeach
            @else
            <div class="alert alert-danger">
                <strong>Lo Sentimos!</strong> No Existen productos relacionados con su Búsqueda.
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                        <h1 class="subtitulo" style="font-size: 24px; color: #1450C9; margin-bottom: 15px; font-weight: 500;  font-family: 'PlutoMedium';">Intente búscar Nuevamente</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 col-sm-12 col-xs-12">
                    <form method="GET" action="{{ secure_url('buscar') }}">
                        <div class="row">
                            <div class="col-sm-12  col-xs-12 col-lg-8">
                                <div class="input-group"> 
                                    <input type="text" name="buscar"  id="buscar" class="form-control" placeholder="Buscar ..." value="{{ old('buscar') }}" autocomplete="off">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-default busqueda" alt="Buscar" ><i class="fa fa-search" aria-hidden="true" id="busqueda"></i></button>
                                    </span>
                                </div><!-- /input-group -->
                            </div><!-- /.col-lg-6 -->
                        </div>  
                    </form>
                </div>
            </div>
        @endif
        </div>
          @include('frontend.includes.paginador')
    </div>
</div>
</div>
</div>


        <!-- Modal Direccion -->
 <div class="modal fade" id="detailCartModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-sucess">
                        <h4 class="modal-title" id="modalLabeldanger">Producto Agregado a tu carrito de compras</h4>
                    </div>
                    <div class="modal-body cartcontenido">


                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-default" data-dismiss="modal">Continuar Comprando</button>
                        <a href="{{ secure_url('cart/show') }}" class="btn  btn-info " >Proceder a Pagar</a>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Direccion -->


@include('frontend.includes.newcart')



@endsection

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ secure_asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ secure_asset('assets/js/cart.js') }}"></script>

    <script>
        jQuery(document).ready(function () {
            new WOW().init();
        });

    </script>

    <script type="text/javascript">

        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
        'event': 'visitaPagina',
        'pageTitle': 'Resultado de la Búsqueda'
        });

    </script>

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P4Q89NF"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
@stop