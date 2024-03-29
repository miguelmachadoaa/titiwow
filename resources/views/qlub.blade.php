@extends('layouts/qlubqueso')

{{-- Page title --}}
@section('title')
Conoce el QLUB del Queso Alpina |  @parent
@stop
@section('meta_tags')


    <link rel="canonical" href="{{$configuracion->seo_url}}" />
    <meta property="og:title" content="Conoce el QLUB de los Quesos Maduros de Alpina  | Alpina GO!">
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
    @if(isset($sliders))
        @foreach($sliders as $s)
            <div class="item">
                <a href="{{ $s->link_slider }}" target="_self">
                    <img src="{{ secure_asset('uploads/sliders/'.$s->imagen_slider ) }}" alt="El Qlub">
                </a>
            </div>
        @endforeach
    @endif
    </div>
    
    <div id="owl-demo-mobile" class="owl-carousel owl-theme visible-xs">
        @foreach($sliders as $s)
            @if($s->imagen_slider_mobile!='0')
            <div class="item">
                <a href="{{ $s->link_slider }}" target="_self">
                    <img src="{{ secure_asset('uploads/sliders/'.$s->imagen_slider_mobile ) }}" class="img-responsive" alt="El Qlub!">
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
    

     <div class="container cont_categorias_qlub fondo_qlub" style="background-repeat: repeat;">

        <div class="row" style="padding: 0; margin:0;">
            <div class="col-sm-offset-3 col-sm-6 col-xs-12" style="padding-top: 1em; ">
                <div class="video-responsive_qlub" style="width: 100%;">
                    <iframe width="" height="" src="https://www.youtube.com/embed/52m3DA8AM9s" title="el QLUB del queso Alpina" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>  
            </div>
        </div>


        <div class="row" style="padding: 0; margin:0;">
            <div class="col-md-12 col-sm-12 text-center" style="padding: 0;margin: 0;">
                <br />
                <h3 class="titulo_qlub">Bienvenidos a:</h3>
            </div>
            <div class="col-xs-12 col-md-12 col-sm-12 text-center imgqlub" style="padding: 0;margin: 0;">
                <img src="{{ secure_url('/').'/assets/images/el_qlub.png' }}" alt="Qlub" title="Qlub" class="img-responsive">   
            </div>
        </div>
    </div>
    <div class="container cont_categorias_qlub">
        
        <div class="row">
            <div class="col-md-12 col-sm-12 text-center">
                <img src="{{secure_url('assets/images/galerias_exp.png')}}" alt="banner" title="banner">
            </div>
            <div class="col-md-12 col-sm-12 wow pulse" data-wow-duration="1.5s">
                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12"  id="caja_categoria_qlub">
                        <div class="">
                            <div class="text-center" id="contenido_qlub">
                                <a href="{{ route('categoria', 'maridajes-de-el-qlub') }}" alt="Kits de Maridaje de el QLUB"><img src="{{ secure_url('/').'/uploads/categorias/maridaje_qlub.jpg' }}" alt="" title="" class="imgRedonda">   </a>
                                <h2 class="categorias_qlub">Kits de Maridaje<h2>
                                <a href="{{ route('categoria', 'maridajes-de-el-qlub') }}" class="botones_qlub boton_qlub" alt="Kits de Maridaje de el QLUB">VER TODOS</a>                             
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12"  id="caja_categoria_qlub">
                        <div class="">
                            <div class="text-center" id="contenido_qlub">
                                <a href="{{ route('categoria', 'productos-de-el-qlub') }}" alt="Productos de el QLUB"><img src="{{ secure_url('/').'/uploads/categorias/productos_qlub.png' }}" alt="" title="" class="imgRedonda">   </a>
                                <h2 class="categorias_qlub">Productos<h2>   
                                <a href="{{ route('categoria', 'productos-de-el-qlub') }}" class="botones_qlub boton_qlub" alt="Productos de el QLUB">VER TODOS</a>                             
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12"  id="caja_categoria_qlub">
                        <div class="">
                            <div class="text-center" id="contenido_qlub">
                            <a href="{{ route('categoria', 'experiencias-de-el-qlub') }}" alt="Experiencias de el QLUB"><img src="{{ secure_url('/').'/uploads/categorias/experiencias_qlub.jpg' }}" alt="" title="" class="imgRedonda">    </a>
                                <h2 class="categorias_qlub">Experiencias<h2> 
                                <a href="{{ route('categoria', 'experiencias-de-el-qlub') }}" class="botones_qlub boton_qlub" alt="Experiencias de el QLUB">VER TODOS</a>                             
                            </div>
                        </div>
                    </div>
                </div>
                <br />
            </div>
        </div>
    </div>
        <!-- //Seccion categoria Fin -->

        <!-- Seccion productos destacados Inicio -->
        <div class="container cont_categorias">
            <div class="row">
                <div class="col-md-12 col-sm-12 text-center">
                    <div class="separador_qlub"></div>
                    <br />
                    <img src="{{secure_url('assets/images/productos_exp.png')}}" alt="banner" title="banner">
                </div>
                <div class="col-md-12 col-sm-12 wow bounceInUp center" data-wow-duration="1.5s"> 
                    <div class="products">
                        <div class="row">

                        @if(count($prods))
                       
                            @php $i=0; @endphp

                            @foreach($prods as $producto)

                                @if($i<12)

                                @if($producto->tipo_producto=='1' || $producto->tipo_producto=='3')

                                    @if(isset($inventario[$producto->id]))

                                        @if($inventario[$producto->id]>0)

                                             @php $i++; @endphp


                                         <div class="col-md-2 col-sm-6 col-xs-6 ">
                                                        <div class="productos_qlub">
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

                                                                

                                                            <div class="product_botones boton_{{ $producto->id }}">

                                                                 @if(isset($inventario[$producto->id]))

                                                                    @if($inventario[$producto->id]>0)

                                                                        

                                                                  

                                                        @if(isset($cart[$producto->slug]))

                                                            <div class="row" style="margin-bottom:5px;">
                                                                <div class="col-sm-10 col-sm-offset-1">
                                                                    <div class="input-group">
                                                                        
                                                                        <span class="input-group-btn">
                                                                                
                                                                                <button data-slug="{{ $producto->slug }}" data-tipo='resta' data-id="{{ $producto->id }}" class="btn btn-danger updatecart" type="button"><i class="fa fa-minus"></i></button>

                                                                              </span>

                                                                              <input id="cantidad_{{ $producto->id }}" name="cantidad_{{ $producto->id }}" type="number" step="1" readonly class="form-control" value="{{ $cart[$producto->slug]->cantidad }}" placeholder="">


                                                                              <span class="input-group-btn">

                                                                                @if($configuracion->maximo_productos==$cart[$producto->slug]->cantidad) 

                                                                                    <button disabled="disabled" data-slug="{{ $producto->slug }}" data-tipo='suma' data-id="{{ $producto->id }}" class="btn btn-success " type="button"><i class="fa fa-plus"></i></button>

                                                                                    @else

                                                                                    <button data-slug="{{ $producto->slug }}" data-tipo='suma' data-id="{{ $producto->id }}" class="btn btn-success updatecart" type="button"><i class="fa fa-plus"></i></button>

                                                                                     @endif 


                                                                              </span>

                                                                            </div><!-- /input-group -->
                                                                          </div><!-- /.col-lg-6 -->
                                                                         
                                                                        </div><!-- /.row -->

                                                                        <a class="btn btn-md btn-vermas" href="{{ route('producto', [$producto->slug]) }}" style="margin-bottom:5px;">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>


                                                                    @else


                                                                         <a class="btn btn-md btn-vermas" href="{{ route('producto', [$producto->slug]) }}">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>

                                                                         @if($producto->tipo_producto=='1')

                                                                         <a 
                                                                         data-slug="{{ $producto->slug }}" 
                                                                         data-price="{{ intval($producto->precio_oferta) }}" 
                                                                         data-id="{{ $producto->id }}" 
                                                                         data-name="{{ $producto->nombre_producto }}" 
                                                                         
                                                                         data-categoria="{{ $producto->nombre_categoria }}" 
                    
                                                                         data-marca="{{ $producto->nombre_marca }}"     
                                                                         
                                                                         data-imagen="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="btn btn-md btn-cart addtocart" href="{{secure_url('cart/addtocart', [$producto->slug])}}" alt="Agregar al Carrito"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></a>


                                                                         @endif


                                                                         



                                                                    @endif


                                                                    @else


                                                                    <a class="btn btn-md btn-vermas" href="{{ route('producto', [$producto->slug]) }}" style="margin-bottom:5px;">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>

                                                                      @endif

                                                                @endif


                                                                    
                                                            </div>
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
                                                        <div class="productos_qlub">
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

                                                                

                                                            <div class="product_botones boton_{{ $producto->id }}">

                                                                 @if(isset($inventario[$producto->id]))

                                                                    @if($inventario[$producto->id]>0)

                                                                        

                                                                  

                                                                    @if(isset($cart[$producto->slug]))

                                                                        <div class="row" style="margin-bottom:5px;">
                                                                          <div class="col-sm-10 col-sm-offset-1">
                                                                            <div class="input-group">
                                                                              <span class="input-group-btn">
                                                                                
                                                                                <button data-slug="{{ $producto->slug }}" data-tipo='resta' data-id="{{ $producto->id }}" class="btn btn-danger updatecart" type="button"><i class="fa fa-minus"></i></button>

                                                                              </span>

                                                                              <input id="cantidad_{{ $producto->id }}" name="cantidad_{{ $producto->id }}" type="number" step="1" readonly class="form-control" value="{{ $cart[$producto->slug]->cantidad }}" placeholder="">


                                                                              <span class="input-group-btn">

                                                                                    @if($configuracion->maximo_productos==$cart[$producto->slug]->cantidad) 

                                                                                    <button disabled="disabled" data-slug="{{ $producto->slug }}" data-tipo='suma' data-id="{{ $producto->id }}" class="btn btn-success " type="button"><i class="fa fa-plus"></i></button>

                                                                                    @else

                                                                                    <button data-slug="{{ $producto->slug }}" data-tipo='suma' data-id="{{ $producto->id }}" class="btn btn-success updatecart" type="button"><i class="fa fa-plus"></i></button>

                                                                                     @endif 


                                                                                

                                                                                

                                                                              </span>

                                                                            </div><!-- /input-group -->
                                                                          </div><!-- /.col-lg-6 -->
                                                                         
                                                                        </div><!-- /.row -->

                                                                        <a class="btn btn-md btn-vermas" href="{{ route('producto', [$producto->slug]) }}" style="margin-bottom:5px;">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>


                                                                    @else
                                                                         <a class="btn btn-md btn-vermas" href="{{ route('producto', [$producto->slug]) }}">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>
                                                                         <a data-slug="{{ $producto->slug }}" data-price="{{ intval($producto->precio_oferta) }}" data-id="{{ $producto->id }}" data-name="{{ $producto->nombre_producto }}"
                                                                         data-categoria="{{ $producto->nombre_categoria }}" 
                    
                    data-marca="{{ $producto->nombre_marca }}" 
                     data-imagen="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="btn btn-md btn-cart addtocart" href="{{secure_url('cart/addtocart', [$producto->slug])}}" alt="Agregar al Carrito"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></a>

                                                                    @endif


                                                                    @else


                                                                    <a class="btn btn-md btn-vermas" href="{{ route('producto', [$producto->slug]) }}" style="margin-bottom:5px;">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>

                                                                      @endif

                                                                @endif


                                                                    
                                                            </div>
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
                        <img src="{{ secure_url('/').'/assets/images/expertos_queseros.png' }}" alt="" title="">   
                    </div>
                </div>
            </div>

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
            new WOW().init();

            $('#BienvenidaModal').modal('show');
        });




    </script>

    <!--page level js ends-->
@stop
