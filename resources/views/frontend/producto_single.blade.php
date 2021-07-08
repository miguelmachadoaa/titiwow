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
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/bootstrap-rating/bootstrap-rating.css') }}">
    <!--end of page level css-->


@stop

{{-- breadcrumb --}}
@section('top')
    <div class="breadcum">
        <div class="container">
            <ol class="breadcrumb">
                <li>
                    <a href="{{ secure_url('/') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#241F48" data-hc="#241F48"></i>Inicio
                    </a>
                </li>
                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#241F48" data-hc="#241F48"></i>
                        @foreach($catprincipal as $catp)
                        <a href="{{ secure_url('categoria/'.$catp->categ_slug) }}" alt="Ver Categoría"> {{ $catp->nombre_categoria }}</a>
                        @endforeach
                </li>
                <li >
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#241F48" data-hc="#241F48"></i>
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

                             @if($producto->tipo_producto=='1' || $producto->tipo_producto=='4')

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
                    <p class="descripcion">{!! $producto->descripcion_corta !!}</p>
                    
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





                        <b>Marca:</b> <a href="{{ secure_url('marcas/'.$producto->marca_slug) }}" class="marca_single" >{{ $producto->nombre_marca}}</a> <br />
                        <b>Categorías:</b> 
                        @foreach ($categos as $cats)
                            @if($loop->last)
                            <a href="{{ secure_url('categoria/'.$cats->categ_slug) }}" class="categoria_single">{{ $cats->nombre_categoria }}</a>.
                            @else
                            <a href="{{ secure_url('categoria/'.$cats->categ_slug) }}" class="categoria_single">{{ $cats->nombre_categoria }}</a>,
                            @endif
                        @endforeach
                       <br />
                        <b>Presentación del Producto:</b> {{ $producto->presentacion_producto}}<br />
                        <b>Medida:</b> {{ $producto->medida}}<br />
                        <b>SKU:</b> {{ $producto->referencia_producto_sap}}<br />
                        <b>Referencia:</b> {{ $producto->referencia_producto}}<br />

                    
                    </p>

                    

                    <div class="box-info-product"> 
                        <div class="row">
                            <div class="col-md-6">

                            <div class="text-big3">
                               
                                @if($descuento==1)

                                @if(isset($precio[$producto->id]))

                                    @switch($precio[$producto->id]['operacion'])

                                        @case(1)

                                            <p id="precio_prod"><span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>
                                            
                                            @break

                                        @case(2)

                                            <p id="precio_prod">
                                                 @if($almacen->descuento_productos=='1')

                                                    @if($producto->mostrar_descuento=='1')

                                                        @if(isset($producto->mostrar))

                                                            @if($producto->mostrar==1)

                                                                @if($producto->precio_base>$producto->precio_oferta)

                                                                    <del class="">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;

                                                                @endif


                                                            @endif

                                                        @endif

                                                        
                                                    @endif


                                                @endif

                                                <span class="precio_base">${{ number_format($producto->precio_base*(1-($precio[$producto->id]['precio']/100)),0,",",".") }}</span></p>


                                            @break

                                        @case(3)

                                            <p id="precio_prod">
                                                 @if($almacen->descuento_productos=='1')

                                                    @if($producto->mostrar_descuento=='1')

                                                        @if(isset($producto->mostrar))

                                                            @if($producto->mostrar==1)

                                                                @if($producto->precio_base>$producto->precio_oferta)

                                                                    <del class="">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;

                                                                @endif


                                                            @endif

                                                        @endif

                                                        
                                                    @endif


                                                @endif

                                                <span class="precio_base">${{ number_format($precio[$producto->id]['precio'],0,",",".") }}</span></p>


                                            @break

                                        
                                    @endswitch

                                @else

                                    <p id="precio_prod">

                                       @if($almacen->descuento_productos=='1')

                                            @if($almacen->descuento_productos=='1')

                                            @if($producto->mostrar_descuento=='1')

                                                @if(isset($producto->mostrar))

                                                    @if($producto->mostrar==1)

                                                        @if($producto->precio_base>$producto->precio_oferta)

                                                            <del class="">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;

                                                        @endif


                                                    @endif

                                                @endif

                                                
                                            @endif


                                        @endif
                                            
                                        @endif

                                            <span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                        @endif

                            @else

                                <p id="precio_prod">
                                     @if($almacen->descuento_productos=='1')

                                            @if($producto->mostrar_descuento=='1')

                                                @if(isset($producto->mostrar))

                                                    @if($producto->mostrar==1)

                                                        @if($producto->precio_base>$producto->precio_oferta)

                                                            <del class="">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;

                                                        @endif


                                                    @endif

                                                @endif

                                                
                                            @endif


                                        @endif


                                    


                                    <span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".").' -'.$producto->operacion }}</span></p>

                                                

                            @endif

                                @if($producto->cantidad==null)
                                    <a href="{{ route('producto', [$producto->slug]) }}" >
                                        <h6 class="pum c1">{{ $producto->pum }}</h6>
                                    </a>
                                    
                                @else

                                    <a href="{{ route('producto', [$producto->slug]) }}" >
                                        <h6 class="pum c2">
                                            {{ $producto->unidad.' a $'.number_format($producto->precio_base/$producto->cantidad,2,",",".") }} pesos
                                        </h6>
                                    </a>

                                @endif

                                

                            <p style="font-size: 0.8em; margin: 0px;" class="span_impuesto">
                                @if($producto->id_impuesto == 1)
                                    IVA incluido
                                @elseif($producto->id_impuesto == 2)
                                    Excento de IVA
                                @endif
                            </p>



                               </div>
                            
                            </div>

                                <div class="col-md-6">
                                    <div class="product_botones boton_{{ $producto->id }}" id="boton_single">

                                            @if(isset($inventario[$producto->id]))

                                            @if($inventario[$producto->id]>0)

                                                @if(isset($cart[$producto->slug]))

                                                    <div class="row">
                                                      <div class="col-sm-10 col-sm-offset-1">
                                                        <div class="input-group">
                                                          <span class="input-group-btn">
                                                            
                                                           <button data-cantidad="{{ $cart[$producto->slug]->cantidad }}" data-slug="{{ $producto->slug }}" data-tipo='resta' data-id="{{ $producto->id }}" data-single="1"
                                                            class="btn btn-danger updatecart" type="button"><i class="fa fa-minus"></i></button>

                                                          </span>

                                                          <input id="cantidad_{{ $producto->id }}" name="cantidad_{{ $producto->id }}" type="number" step="1" readonly class="form-control" value="{{ $cart[$producto->slug]->cantidad }}" placeholder="">


                                                          <span class="input-group-btn">

                                                            @if($configuracion->maximo_productos==$cart[$producto->slug]->cantidad)

                                                            <button disabled="disabled" data-cantidad="{{ $cart[$producto->slug]->cantidad }}" data-slug="{{ $producto->slug }}" data-tipo='suma' data-id="{{ $producto->id }}" data-single="1" class="btn btn-success " type="button"><i class="fa fa-plus"></i></button> 


                                                            @else

                                                                <button data-cantidad="{{ $cart[$producto->slug]->cantidad }}" data-slug="{{ $producto->slug }}" data-tipo='suma' data-id="{{ $producto->id }}" data-single="1" class="btn btn-success updatecart" type="button"><i class="fa fa-plus"></i></button>

                                                             @endif 
                                                            
                                                            

                                                          </span>

                                                        </div><!-- /input-group -->
                                                      </div><!-- /.col-lg-6 -->
                                                     
                                                    </div><!-- /.row -->

                                                @else



                                                         @if($producto->tipo_producto=='1' || $producto->tipo_producto=='4')

                                                          <a data-slug="{{ $producto->slug }}" data-price="{{ intval($producto->precio_oferta) }}" data-id="{{ $producto->id }}" data-name="{{ $producto->nombre_producto }}" data-imagen="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="btn btn-md btn-cart addtocart" data-single="1" href="{{secure_url('cart/addtocart', [$producto->slug])}}" alt="Agregar al Carrito">Agregar al Carrito<i class="fa fa-cart-arrow-down" aria-hidden="true" style="margin-left:10px"></i></a>

                                                        @else

                                                            @if(isset($combos[$producto->id]))


                                                             <a data-slug="{{ $producto->slug }}" data-price="{{ intval($producto->precio_oferta) }}" data-id="{{ $producto->id }}" data-name="{{ $producto->nombre_producto }}" data-imagen="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="btn btn-md btn-cart addtocart" data-single="1" href="{{secure_url('cart/addtocart', [$producto->slug])}}" alt="Agregar al Carrito">Agregar al Carrito<i class="fa fa-cart-arrow-down" aria-hidden="true" style="margin-left:10px"></i></a>

                                                            @else



                                                            @endif

                                                        @endif
                                                    



                                                     
                                                       

                                                    
                                                @endif
                                            @endif
                                            @endif


                                                
                                        </div>
                                    </div>
                        </div>
                    </div>

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
        <div class="row">
            <div class="col-sm-12">
                <!-- Tabbable-Panel Start -->
                <div class="tabbable-panel">
                    <!-- Tabbablw-line Start -->
                    <div class="tabbable-line">
                        <!-- Nav Nav-tabs Start -->
                        <ul class="nav nav-tabs ">
                            <li class="active">
                                <a href="#tab_default_1" data-toggle="tab">
                                Descripción </a>
                            </li>
                           
                        </ul>
                        <!-- //Nav Nav-tabs End -->
                        <!-- Tab-content Start -->
                        <div class="tab-content ">

                            @if($producto->enlace_youtube==null)

                            @else

                            <div class=" col-sm-6 col-xs-12 col-centered">
                                <div class="video-responsive">
                                    <iframe src="https://www.youtube.com/embed/{{$producto->enlace_youtube}}" frameborder="0" width="100%" height="100%"  allowfullscreen></iframe> 
                               </div>  
                            </div>

                            <div class="clearfix"></div>

                            <br />

                            @endif


                            <div class="tab-pane active" id="tab_default_1">
                                <p>{!! $producto->descripcion_larga !!}</p>
                            </div>
                            
                            <!-- Tab-content End -->
                        </div>
                        <!-- //Tabbable-line End -->
                    </div>
                    <!-- Tabbable_panel End -->
                </div>
            </div>
        </div>

        <script type="text/javascript">

        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
        'event': 'vistaProducto',
        'pageTitle': '{{ $producto->nombre_producto}}',
        'nombreProducto': '{{ $producto->nombre_producto}}',
        'precioProducto': '{{ $producto->precio_oferta}}',
        'nombreMarca': '{{ $producto->nombre_marca}}',
        'skuProducto': '{{ $producto->referencia_producto_sap}}',
        'referenciaProducto': '{{ $producto->referencia_producto}}',
        });

    </script>
        <!-- Productos Relacionados -->
        <div class="products">
        <div class="row">
        @if(count($relacionados)>0)

        @if(count($prods)>0)
        <div class="col-md-12 col-sm-12 text-center">

                    <h2 class="catego">También te puede Interesar</h2>

                    <div class="separador"></div>
                </div>

            @foreach($prods as $producto)

                @if(isset($inventario[$producto->id]))

                    @if($inventario[$producto->id]>0)

                        @include('frontend.producto')

                        @if ($loop->iteration % 4 == 0)

                            </div>
                            
                            <div class="row">
                        @endif
                    
                    @endif

                @endif
            @endforeach
           
       
        @endif
        @endif

    </div>
    </div>
<!-- Productos Relacionados -->
        
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
        jQuery(document).ready(function () {
            new WOW().init();
        });

    </script>

   

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T5HPJTZ"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->



@stop
