@extends('layouts/default')

{{-- Page title --}}
@section('title')
Todos los productos
 @parent
@stop
@section('meta_tags')

<link rel="canonical" href="{{$url}}" />

    <meta property="og:title" content="Productos | Alpina GO!">
<meta property="og:description" content="">
<meta property="og:revisit-after" content="3 days">
<meta property="og:image" content="{{ $configuracion->seo_image }}" />
    <meta property="og:url" content="{{$url}}" />
    <meta name="description" content="{{$configuracion->seo_description}}"/>


   @if(isset($producto->robots))

    @if($producto->robots==null)

    @else

        <meta name="robots" content="{{$producto->robots}}">
        
    @endif

@else

    <meta name="robots" content="index, follow">
    
@endif


   

    <meta name="twitter:card" content="summary">
<meta name="twitter:site" content="{{'@'.$configuracion->cuenta_twitter}}">
<meta name="twitter:description" content="{{$configuracion->seo_description}}">
<meta name="twitter:title" content="{{ $configuracion->seo_title}}">
<meta name="twitter:image" content="{{$configuracion->seo_url}}">

@endsection

{{-- page level styles --}}
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
                    <a href="{{ secure_url('/') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#ffffff" data-hc="#ffffff"></i>Inicio
                    </a>
                </li>
                <li class="hidden-md hidden-lg">
                    <a href="{{ secure_url('/') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#ffffff" data-hc="#ffffff"></i>
                    </a>
                </li>
                <li >
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#ffffff" data-hc="#ffffff"></i>
                    <a href="#">Categoría</a>
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

    <div class="products">

        <div class="row">
            
        @if(!$productos->isEmpty())

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

                @else

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
                <strong>Lo Sentimos!</strong> No Existen productos en esta categoría.
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
        'pageTitle': 'Todos los productos'
        });

    </script>

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P4Q89NF"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
@stop