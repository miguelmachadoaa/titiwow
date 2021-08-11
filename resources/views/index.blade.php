@extends('layouts/default')

{{-- Page title --}}
@section('title')
{{ $configuracion->seo_title}} @parent
@stop
@section('meta_tags')


    <link rel="canonical" href="{{$configuracion->seo_url}}" />
    <meta property="og:title" content="{{ $configuracion->seo_title}}  | Alpina GO!">
    <meta property="og:type" content="{{$configuracion->seo_type}}" />
    <meta property="og:image" content="{{$configuracion->seo_image}}" />
    <meta property="og:site_name" content="{{$configuracion->seo_site_name}}" />
    <meta property="og:url" content="{{$configuracion->seo_url}}" />
    <meta property="og:description" content="{{$configuracion->seo_description}}">
    <meta name="description" content="{{$configuracion->seo_description}}"/>
    
    <meta property="og:revisit-after" content="3 days">


  
   @if(isset($producto->robots))

    @if($producto->robots==null)

    @else

        <meta name="robots" content="{{$producto->robots}}">
        
    @endif

@else

    <meta name="robots" content="index, follow">
    
@endif

    @if(isset($configuracion->cuenta_twitter))
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="{{'@'.$configuracion->cuenta_twitter}}">
<meta name="twitter:description" content="{{$configuracion->seo_description}}">
<meta name="twitter:title" content="{{ $configuracion->seo_title}}">
<meta name="twitter:image" content="{{$configuracion->seo_image}}">
@endif



@endsection

{{-- page level styles --}}
@section('header_styles')
    <!--page level css starts-->
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/cart.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/tabbular.css') }}">
    
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/animate/animate.min.css') }}" />
    
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/jquery.circliful.css') }}">
    
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/owl_carousel/css/owl.carousel.css') }}">
    
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/owl_carousel/css/owl.theme.css') }}">

    <link rel="stylesheet" type="text/css" href="https://csshake.surge.sh/csshake.min.css">
    <style>
    
    .modal-header {
    min-height: 16.43px;
    padding: 15px;
    border-bottom: 1px solid #e5e5e5;
    background: #241F48;
    color: #fff;
}


</style>



    <!--end of page level css-->
@stop



{{-- slider --}}
@section('top')
    <!--Carousel Start -->
    <div id="owl-demo" class="owl-carousel owl-theme hidden-xs">
        @foreach($sliders as $s)
            <div class="item">
                <a href="{{ $s->link_slider }}" target="_self">
                    <img src="{{ secure_asset('uploads/sliders/'.$s->imagen_slider ) }}" alt="Alpina Go!">
                </a>
            </div>
        @endforeach
    </div>


    <div id="owl-demo-mobile" class="owl-carousel owl-theme visible-xs">
        @foreach($sliders as $s)
            @if($s->imagen_slider_mobile!='0')
            <div class="item">
                <a href="{{ $s->link_slider }}" target="_self">
                    <img src="{{ secure_asset('uploads/sliders/'.$s->imagen_slider_mobile ) }}" class="img-responsive"  alt="Alpina Go!">
                </a>
            </div>
            @endif
        @endforeach
    </div>
    <!-- //Carousel End -->
@stop

{{-- content --}}
@section('content')





@if (session('success'))


        <div class="modal fade" id="BienvenidaModal" role="dialog" aria-labelledby="modalLabeldanger">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-sucess">
                        <h4 class="modal-title" id="modalLabeldanger">Gracias por su visita</h4>
                </div>
                
                <div class="modal-body cartcontenido " style="text-align: center;">
                
                    <h3>{{session('success')}}</h3> 

                    <p>@if(isset($role->role_id))

                        @if($role->role_id!='9')

                            @if ($configuracion->explicacion_precios=='1')
                                {{-- expr --}}

                                <div class="col-sm-12" style="padding:0; margin:0;">
                                    
                                    <a target="_blank" href="#"><img src="{{secure_url('uploads/files/banner-300x100.jpg')}}" alt="banner" title="banner"></a>

                                </div>

                            @endif


                        @endif
                    
                    @endif

                </div>


                <div class="modal-footer">
                    <button type="button"  class="btn  btn-primary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


            
        @endif
        @if($almacen->id == 90000)
        <div class="container cont_categorias">
            <div class="row">
                <div class="col-md-12 col-sm-12 text-center hidden-xs">
                    <a href="#" ><img src="{{ secure_url('/').'/assets/images/ancheta_d.jpg' }}" alt="Arma tu Ancheta" title="Arma tu Ancheta" class="img-responsive"></a>
                </div>
                <div class="col-md-12 col-sm-12 text-center visible-xs">
                    <a href="#" ><img src="{{ secure_url('/').'/assets/images/ancheta_m.jpg' }}" alt="Arma tu Ancheta" title="Arma tu Ancheta" class="img-responsive"></a>
                </div>
            </div>
        </div>
        @endif
    <!-- //Layout Section Start -->
    <!-- Seccion categoria Inicio -->


  


    <div class="container cont_categorias">
        <div class="row">
            <div class="col-md-12 col-sm-12 text-center">
                <h3 class="catego almacen{{$almacen->id}}">Categorías</h3>
                <div class="separador"></div>
            </div>
            <div class="col-md-12 col-sm-12 wow pulse" data-wow-duration="1.5s">
                <div class="row">
                    @if(!$categorias->isEmpty())
                        @foreach($categorias as $categoria)
                            <div class="col-md-4 col-sm-12 col-xs-12"  id="caja_categoria">
                                <div class="{{ $categoria->css_categoria }}">
                                    <div class="layercat">
                                        <div class="text-align:center;" id="contenido_cat">
                                            <h2>{{ $categoria->nombre_categoria }}</h2>
                                            <a href="{{ route('categoria', [$categoria->slug]) }}" class="botones_cat boton_cat">VER TODOS</a>                                
                                        </div>
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
    </div>
        <!-- //Seccion categoria Fin -->

        <!-- Seccion productos destacados Inicio -->
        <div class="container cont_categorias">
            <div class="row">
                <div class="col-md-12 col-sm-12 text-center">
                    <h3 class="catego">Productos Más Vendidos</h3>
                    <div class="separador"></div>
                </div>
                <div class="col-md-12 col-sm-12 wow bounceInUp center" data-wow-duration="1.5s"> 
                    <div class="products">
                        <div class="row">


                        @php $i=0; @endphp

                        @if(count($prods))

                            @foreach($prods as $producto)

                                
                            










                            @if($i<12)


                                @if($producto->tipo_producto=='1' || $producto->tipo_producto=='3')


                                    @if(isset($inventario[$producto->id]))


                                        @if($inventario[$producto->id]>0)



                                            @php $i++; @endphp


                                            <div class="col-md-2 col-sm-6 col-xs-6 ">
                                            
                                                <div class="productos">
                                            
                                                    <div class="text-align:center;">
                                                
                                                        <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ secure_url('/').'/uploads/productos/250/'.$producto->imagen_producto }}" alt="{{ $producto->nombre_producto }}" title="{{ $producto->nombre_producto }}" class="img-responsive homi"></a>

                                                        @if(isset($inventario[$producto->id]))
                                                        
                                                            @if($inventario[$producto->id]<=0)

                                                                <img class="agotado" style="" src="{{ secure_url('/').'/uploads/files/agotado.png' }}" alt="Agotado" title="Agotado">

                                                            @endif

                                                        @endif
                                                            
                                                    </div>
                                            
                                                    <a href="{{ route('producto', [$producto->slug]) }}" >
                                                        <h3>{{ $producto->nombre_producto }}</h3>
                                                    </a>
                                                        
                                                    <a href="{{ route('producto', [$producto->slug]) }}" >
                                                        <h6 class="text-align:center;">{{ $producto->presentacion_producto }}</h6>
                                                    </a>
                                            
                                                    <div class="product_info">

                                                    @if($descuento==1)

                                                        @if(isset($precio[$producto->id]))

                                                            <p id="precio_prod">
                                                                        
                                                                @include('producto_precio_tachado')

                                                                <span class="precio_base">${{ number_format($producto->precio_oferta,0,",",".") }}</span>
                                                            </p>

                                                            @if($producto->cantidad==null)
                                            
                                                                <a href="{{ route('producto', [$producto->slug]) }}" >
                                                                        <h6 class="pum c9">{{ $producto->pum }}</h6>
                                                                </a>

                                                            @else

                                                                <a href="{{ route('producto', [$producto->slug]) }}" >
                                                                    <h6 class="pum c10">
                                                                        {{ $producto->unidad.' a $'.number_format($producto->precio_oferta/$producto->cantidad,2,",",".") }} pesos
                                                                    </h6>
                                                                </a>

                                                            @endif


                                                        @else

                                                            <p id="precio_prod"><span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                                            @if($producto->cantidad==null)
                                                                
                                                                <a href="{{ route('producto', [$producto->slug]) }}" >
                                                                    
                                                                    <h6 class="pum c11">{{ $producto->pum }}</h6>
                                                                </a>

                                                            @else

                                                                <a href="{{ route('producto', [$producto->slug]) }}" >
                                                                    
                                                                    <h6 class="pum c12">
                                                                        {{ $producto->unidad.' a $'.number_format($producto->precio_oferta/$producto->cantidad,2,",",".") }} pesos
                                                                    </h6>
                                                                </a>

                                                            @endif
                                                                    

                                                        @endif

                                                    @else

                                                        <p id="precio_prod">
                                                    
                                                        @include('producto_precio_tachado')
                                                    
                                                        <span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

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

                                                    @endif

                                                    @include('producto_botones')
                                        
                                        </div>
                                    
                                    </div>

                                </div>
                                @if ($i % 6 == 0)
                                    </div>
                                    <div class="row">
                                @endif
                                            

                                @endif

                                @endif

                                @else

                                @if(isset($combos[$producto->id]))

                                @php $i++; @endphp

                                <div class="col-md-2 col-sm-6 col-xs-6 ">
                                    
                                    <div class="productos">
                                        
                                        <div class="text-align:center;">
                                            
                                            <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ secure_url('/').'/uploads/productos/250/'.$producto->imagen_producto }}" alt="{{ $producto->nombre_producto }}" title="{{ $producto->nombre_producto }}" class="img-responsive homi"></a>

                                            @if(isset($inventario[$producto->id]))

                                                @if($inventario[$producto->id]<=0)

                                                    <img class="agotado" style="" src="{{ secure_url('/').'/uploads/files/agotado.png' }}" alt="Agotado" title="Agotado">

                                                @endif

                                            @endif
                                                            
                                        </div>
                                        
                                        <a href="{{ route('producto', [$producto->slug]) }}" ><h3>{{ $producto->nombre_producto }}</h3></a>
                                        
                                        <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="text-align:center;">{{ $producto->presentacion_producto }}</h6></a>
                                        
                                        <div class="product_info">

                                        @if($descuento==1)

                                            @if(isset($precio[$producto->id]))

                                                <p id="precio_prod">
                                                                        
                                                    @include('producto_precio_tachado')

                                                    <span class="precio_base">${{ number_format($producto->precio_oferta,0,",",".") }}</span>
                                                </p>

                                                @if($producto->cantidad==null)
                                                    
                                                    <a href="{{ route('producto', [$producto->slug]) }}" >
                                                            <h6 class="pum c3">{{ $producto->pum }}</h6>
                                                    </a>
                                                        
                                                @else

                                                    <a href="{{ route('producto', [$producto->slug]) }}" >
                                                        <h6 class="pum c4">
                                                                {{ $producto->unidad.' a $'.number_format($producto->precio_base/$producto->cantidad,2,",",".") }} pesos
                                                        </h6>
                                                    </a>

                                                @endif

                                            @else

                                                <p id="precio_prod"><span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                                @if($producto->cantidad==null)
                                                    <a href="{{ route('producto', [$producto->slug]) }}" >
                                                        <h6 class="pum c5">{{ $producto->pum }}</h6>
                                                    </a>
                                                    
                                                @else

                                                    <a href="{{ route('producto', [$producto->slug]) }}" >
                                                        <h6 class="pum c6">
                                                            {{ $producto->unidad.' a $'.number_format($producto->precio_base/$producto->cantidad,2,",",".") }} pesos
                                                        </h6>
                                                    </a>

                                                @endif

                                            @endif

                                        @else

                                            <p id="precio_prod">
                                                
                                            @include('producto_precio_tachado')
                                            
                                            <span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                            @if($producto->cantidad==null)
                                                <a href="{{ route('producto', [$producto->slug]) }}" >
                                                    <h6 class="pum c7">{{ $producto->pum }}</h6>
                                                </a>
                                                
                                            @else

                                                <a href="{{ route('producto', [$producto->slug]) }}" >
                                                    <h6 class="pum c8">
                                                        {{ $producto->unidad.' a $'.number_format($producto->precio_base/$producto->cantidad,2,",",".") }} pesos
                                                    </h6>
                                                </a>

                                            @endif

                                        @endif

                                        @include('producto_botones')
                                    
                                    </div>

                                </div>

                                </div>
                                @if ($i % 6 == 0)
                                </div>
                                <div class="row">
                                @endif


                                    @endif
                                        
                                @endif

                                @endif<!--//endif $i<12-->
















































                                
                            @endforeach

                        @else

                            <div class="alert alert-danger">
                                <strong>Lo Sentimos!</strong> No Existen productos en esta categoría.
                            </div>

                        @endif
        
        </div>
    
    </div>
</div>
</div>
</div>
            <!-- //Seccion productos destacados Fin -->
            <!-- Seccion marcas Inicio -->
            <div class="container cont_categorias">
                <div class="row">
                    <div class="col-md-12 col-sm-12 text-center">
                        <h3 class="catego">Nuestras Marcas</h3>
                        <div class="separador"></div>
                    </div>
                    <div class="col-md-12 col-sm-12 wow bounceInUp center" data-wow-duration="1.5s"> 
                        <div class="row">
                            @if(!$marcas->isEmpty())
                                @foreach($marcas as $marca)
                                    <div class="col-md-2 col-sm-6 col-xs-6" >
                                        <div class="brands">
                                            <a href="{{ route('marcas', [$marca->slug]) }}" >
                                                    <img src="{{ secure_url('/').'/uploads/marcas/'.$marca->imagen_marca }}" class="img-responsive" title="{{ $marca->nombre_marca }}" alt="{{ $marca->nombre_marca }}">
                                            </a>
                                        </div>
                                    </div>
                                    @if ($loop->iteration % 6 == 0)
                                        </div>
                                        <div class="row">
                                    @endif
                                @endforeach
                            @else
                            <div class="alert alert-danger">
                                <strong>Lo Sentimos!</strong> No Existen Marcas para Mostrar.
                            </div>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        <!-- //Seccion marcas Fin -->

        <h1 style="font-size: 24px; text-align: center;" class="catego">{{$configuracion->h1_home}}</h1>



    </div>
    <!-- //Container End -->






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
                    <a href="{{ secure_url('order/detail') }}" class="btn  btn-info " >Proceder a Pagar</a>
                </div>
            </div>
        </div>
    </div>

<!-- Modal Direccion -->

<input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">

@include('frontend.includes.newcart')

@stop
{{-- footer scripts --}}
@section('footer_scripts')
    <!-- page level js starts-->
    <script type="text/javascript" src="{{ secure_asset('assets/js/frontend/jquery.circliful.js') }}"></script>
    
      <script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>


    <script type="text/javascript" src="{{ secure_asset('assets/vendors/wow/js/wow.min.js') }}" ></script>

    <script type="text/javascript" src="{{ secure_asset('assets/vendors/owl_carousel/js/owl.carousel.min.js') }}"></script>

   
    <script type="text/javascript" src="{{ secure_asset('assets/js/frontend/carousel.js') }}"></script>

    <script type="text/javascript" src="{{ secure_asset('assets/js/frontend/index.js') }}"></script>
    
    <script type="text/javascript" src="{{ secure_asset('assets/js/cart.js') }}"></script>

      <script>
        jQuery(document).ready(function () {

           // Won't work


            new WOW().init();

            $('#BienvenidaModal').modal('show');
        });




    </script>

    <script type="text/javascript">

        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
        'event': 'visitaPagina',
        'pageTitle': '{{ $configuracion->seo_title}}'
        });

    </script>

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T5HPJTZ"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->



    <!--page level js ends-->
@stop
