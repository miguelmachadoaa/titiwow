@extends('layouts/default')

{{-- Page title --}}
@section('title')
{{ $producto->nombre_producto}} @parent
@stop
@section('meta_tags')


<link rel="canonical" href="{{$url}}" />
<meta property="og:title" content="{{ $producto->seo_titulo }}| Alpina GO!">
<meta property="og:description" content="{{ $producto->seo_descripcion }}">
<meta property="og:image" content="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" />
<meta property="og:url" content="{{$url}}" />
<meta name="description" content="{{$producto->seo_descripcion}}"/>

@if(isset($configuracion->cuenta_twitter))
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="{{'@'.$configuracion->cuenta_twitter}}">
<meta name="twitter:description" content="{{ $producto->seo_descripcion }}">
<meta name="twitter:title" content="{{ $producto->seo_titulo }}">
<meta name="twitter:image" content="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}">

@endif


@if(isset($producto->robots))

    @if($producto->robots==null)

    @else

        <meta name="robots" content="{{$producto->robots}}">
        
    @endif

@else

    <meta name="robots" content="index, follow">
    
@endif



<script type="application/ld+json">
      {
        "@context": "https://schema.org/",
        "@type": "Product",
        "name": "{{$producto->nombre_producto}}",
        "image": "{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}",
        
        "description": "{{$producto->descripcion_corta}}",
        "sku": "{{$producto->referencia_producto}}",
        "mpn": "{{$producto->referencia_producto_sap}}",
        "brand": {
          "@type": "Brand",
          "name": "{{$producto->nombre_marca}}"
        },
        "offers": {
          "@type": "AggregateOffer",
          "availability": "http://schema.org/InStock",
          "offerCount": "5",
          "lowPrice": "{{$producto->precio_oferta}}",
          "highPrice": "{{$producto->precio_base}}",
          "priceCurrency": "COP"
        }
      }
    </script>






@endsection

{{-- page level styles --}}
@section('header_styles')
    <!--page level css starts-->
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/cart.css') }}">
    
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/cart.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/tabbular.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/verticalform.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/bootstrap-rating/bootstrap-rating.css') }}">
    <!--end of page level css-->


@stop

{{-- breadcrumb --}}
@section('top')
    <div class="breadcum">
        <div class="container">
            <ol class="breadcrumb">
                <li>
                    <a href="{{ secure_url('/') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>Inicio
                    </a>
                </li>
                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>
                        @foreach($catprincipal as $catp)
                        <a href="{{ secure_url('categoria/'.$catp->categ_slug) }}" alt="Ver Categoría"> {{ $catp->nombre_categoria }}</a>
                        @endforeach
                </li>
                <li >
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>
                    <a href="#">{{ $producto->nombre_producto}}</a>
                </li>
            </ol>
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
    <!-- Container Section Start -->
    <div class="container contain_body">
        <!--item view start-->
        <div class="row">
            <div class="mart10">
                <!--product view-->
                <div class="col-sm-4 col-md-4">
                    <div class="row">
                        <div class="product_wrapper">
                            <img src="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" data-zoom-image="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="img-responsive" alt="{{ $producto->nombre_producto}}" title="{{ $producto->nombre_producto}}"/>

                             @if($producto->tipo_producto=='1')

                                @if(isset($inventario[$producto->id]))

                                    @if($inventario[$producto->id]<=0)

                                        <img style="    position: absolute;    top: 9px;    left: 0em;    float: left;    width: 8em !important;    height: 8em !important;" class="" style="" src="{{ secure_url('/').'/uploads/files/agotado.png' }}" alt="">

                                    @endif

                                @endif

                            @else

                                @if(isset($combos[$producto->id]))

                                @else

                                        <img style="    position: absolute;    top: 9px;    left: 0em;    float: left;    width: 8em !important;    height: 8em !important;" class="" style="" src="{{ secure_url('/').'/uploads/files/agotado.png' }}" alt="">

                                @endif


                            @endif




                        </div>
                    </div>
                </div>
                <!--individual product description-->
                <div class="col-sm-8 col-md-8">
                    <h1 class="text-primary" id="titulo_single">{{ $producto->nombre_producto}} </h1>
                    <p class="descripcion">{{ $producto->descripcion_corta}}</p>
                    
                    <p class="">


                         @if($producto->tipo_producto=='2')

                                @if(isset($combos[$producto->id]))

                                <b style="color: #143473;">Incluye:</b>

                                    @foreach($combos[$producto->id] as $cp)

                                        <div class="col-xs-12" style=" @if($loop->last) {{'margin-bottom:10px;'}} @endif padding-left: 4em;">

                                            <h6><a target="_blank"  href="{{ route('producto', [$cp->slug]) }}" style="color: #143473; " ><i class="fa fa-angle-double-right"></i>{{$cp->cantidad.'- '.$cp->nombre_producto}}</a></h6>

                                        </div>  

                                    @endforeach

                                @endif


                            @endif





                        <b>Marca:</b> <a href="{{ secure_url('marcas/'.$producto->marca_slug) }}" >{{ $producto->nombre_marca}}</a> <br />
                        <b>Categorías:</b> 
                        @foreach ($categos as $cats)
                            @if($loop->last)
                            <a href="{{ secure_url('categoria/'.$cats->categ_slug) }}" >{{ $cats->nombre_categoria }}</a>.
                            @else
                            <a href="{{ secure_url('categoria/'.$cats->categ_slug) }}" >{{ $cats->nombre_categoria }}</a>,
                            @endif
                        @endforeach
                       <br />
                        <b>Presentación del Producto:</b> {{ $producto->presentacion_producto}}<br />
                        <b>Medida:</b> {{ $producto->medida}}<br />
                        <b>SKU:</b> {{ $producto->referencia_producto_sap}}<br />
                        <b>Referencia:</b> {{ $producto->referencia_producto}}<br />

                    
                    </p>

                    

                  

                            @if ($configuracion->explicacion_precios=='1')

                            <div class="col-sm-12" style="padding:0; margin:0;">
                        
                        <a href="#" target="_blank"><img src="{{secure_url('uploads/files/banner-750x100.jpg')}}" alt="banner"></a>

                    </div>

                            @endif


                    
                    
                </div>
            </div>
        </div>
        <!--item view end-->
        <!--item desciption start-->

        <div class="widget-body">
        <div class="row">


            

            <div class="col-sm-12">
                
                <h1  class="text-primary tetx-center" id="titulo_single">Arma tu ancheta  </h1>

            </div>

            <!--div class="col-sm-3">

                <div class="vrtwiz">

                    <ul class="verticalwiz">

                        @foreach($anchetas_categorias as $ac)

                            <li class="@if($loop->index==0) active @endif" data-target="#step{{$loop->index+1}}">
                                <a href="#tab{{$loop->index+1}}" data-toggle="tab" class="active"> <span class="step">Paso {{$loop->index+1}}</span> <span class="title">{{$ac->nombre_categoria}}</span> </a>
                            </li>

                        @endforeach

                    </ul>

                    <div class="clearfix"></div>
                </div>
                
            </div-->



                <div class="col-sm-12 col-md-12">
                                <div class="rightab">
                                    <div class="tab-content">

                    @foreach($anchetas_categorias as $ac)

                    <div class="tab-pane @if($loop->index==0) active @endif" id="tab{{$loop->index+1}}">
                                            <br>
                                            <h3><strong>Paso {{$loop->index+1}}  </strong> - Seleccione {{$ac->nombre_categoria}}</h3>

                                        @foreach($ac->productos as $p)

                                        <div class="col-sm-4" style=" ">

                                            <div class="row" style="">
                                                
                                                <div class="col-sm-5" style="padding: 0;margin: 0;">
                                                     <a href="{{ route('producto', [$p->slug]) }}" ><img src="{{ secure_url('/').'/uploads/productos/250/'.$p->imagen_producto }}" alt="{{ $p->nombre_producto }}" title="{{ $p->nombre_producto }}" class="img-responsive"></a>

                                                </div>

                                                <div class="col-sm-7">

                                                    <p> {{$p->nombre_producto}}</p>

                                                    <a href="{{ route('producto', [$p->slug]) }}" ><h6 class="text-align:center;">{{ $p->presentacion_producto }}</h6></a>

                                                    <!--button type="button" class="btn btn-info ">Seleccionar</button-->
                                                    
                                                </div>
                                            </div>



                                        

                                           
                                       




















                                        

                                           

                                         


                                        </div>

                                        


                                        @endforeach

                                         <div class="form-actions">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <ul class="pager wizard no-margin">
                                                            @if($loop->index==0)

                                                            <li class="previous disabled">
                                                                <a href="#tab{{$loop->index}}" data-toggle="tab" class="" class="btn btn-lg btn-primary"> Anterior </a>
                                                            </li>
                                                            @else

                                                            <li class="previous ">
                                                                <a href="#tab{{$loop->index}}" data-toggle="tab" class="" class="btn btn-lg btn-primary"> Anterior </a>
                                                            </li>



                                                            @endif
                                                            
                                                            <li class="next">
                                                                <a href="#tab{{$loop->index+2}}" data-toggle="tab" class="" class="btn btn-lg btn-primary"> Siguiente </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>


                                         </div>
                                        
                                            
                                        
                                   

                    @endforeach

                     </div>

                                </div>

                            </div>
                

        
    </div>
    </div>


    </div>
    <!-- //Container Section End -->





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

<input type="hidden" name="single" id="single" value="1">

@include('frontend.includes.newcart')


@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!--page level js start-->
    <script type="text/javascript" src="{{ secure_asset('assets/vendors/bootstrap-rating/bootstrap-rating.js') }}"></script>
   
    <!--page level js start-->

    <script src="{{ secure_asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ secure_asset('assets/js/cart.js') }}"></script>

    <script>


        $('.anchetabtn').on('click', function(){
            id=$(this).data('id');

            $('.anchetapanel').fadeOut('fast', function() { });
            $('.'+id).fadeIn('fast', function() { });
        });



        jQuery(document).ready(function () {
            new WOW().init();
        });

    </script>

@stop
