
@extends('layouts/default')

{{-- Page title --}}
@section('title')
Productos @parent
@stop
@section('meta_tags')
<meta property="og:title" content="Productos | Alpina Go!">
<meta property="og:description" content="Productos de Alpina Go!">
<meta property="og:robots" content="index, follow">
<meta property="og:revisit-after" content="3 days">
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
                <li>
                    <a href="{{ secure_url('/') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>Inicio
                    </a>
                </li>
                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>
                    <a href="#">Todos los Productos</a>
                </li>
            </ol>
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container contain_body">
<div class="row">
<div class="col-md-12">
    <div class="products">
        <!-- Categoria 1 -->
        <div class="row">
            <div class="col-md-3">   
                <div class="categoriaprod" id="cat1" style="background-image:url({{ secure_url('/').'/assets/img/categorias/leche.jpg' }});background-repeat: no-repeat;background-size: cover;">
                    <div class="layercat">
                        <div class="text-align:center;" id="contenido_list">
                            <h2>Leche</h2>
                            <a href="{{ secure_url('categoria/leche') }}" class="botones_cat boton_cat">VER TODOS</a>                                
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                @if(count($leche)>0)
                    @foreach($leche as $producto)
                        <div class="col-md-3 col-sm-6 col-xs-6">
                            <div class="productos">
                                <div class="text-align:center;">
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="img-responsive"></a>
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

                                                        <p id="precio_prod"><del class="">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*(1-($precio[$producto->id]['precio']/100)),0,",",".") }}</span></p>
                                                        @break

                                                    @case(3)

                                                        <p id="precio_prod"><del class="">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;<span class="precio_base">${{ number_format($precio[$producto->id]['precio'],0,",",".") }}</span></p>
                                                        @break
                                                    
                                                @endswitch

                                            @else

                                                <p id="precio_prod"><span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                            @endif

                                        @else

                                            <p id="precio_prod"><del class="@if($descuento==1) hidden @endif">${{ number_format($producto->precio_base,0,",",".")}}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                        @endif
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="pum">{{ $producto->pum }}</h6></a>

                                    <div class="product_botones boton_{{ $producto->id }}">

                                                @if(isset($cart[$producto->slug]))

                                                    <div class="row" style="margin-bottom:5px;">
                                                      <div class="col-sm-10 col-sm-offset-1">
                                                        <div class="input-group">
                                                          <span class="input-group-btn">
                                                            
                                                            <button data-slug="{{ $producto->slug }}" data-tipo='resta' data-id="{{ $producto->id }}" class="btn btn-danger updatecart" type="button"><i class="fa fa-minus"></i></button>

                                                          </span>

                                                          <input id="cantidad_{{ $producto->id }}" name="cantidad_{{ $producto->id }}" type="number" step="1" readonly class="form-control" value="{{ $cart[$producto->slug]->cantidad }}" placeholder="">


                                                          <span class="input-group-btn">

                                                            <button data-slug="{{ $producto->slug }}" data-tipo='suma' data-id="{{ $producto->id }}" class="btn btn-success updatecart" type="button"><i class="fa fa-plus"></i></button>

                                                            

                                                          </span>

                                                        </div><!-- /input-group -->
                                                      </div><!-- /.col-lg-6 -->
                                                     
                                                    </div><!-- /.row -->

                                                    <a class="btn btn-md btn-vermas2" href="{{ route('producto', [$producto->slug]) }}" style="margin-bottom:5px;">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>

                                                @else

                                                    <a class="btn btn-md btn-vermas2" href="{{ route('producto', [$producto->slug]) }}">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>
                                                    <a data-slug="{{ $producto->slug }}" data-price="{{ intval($producto->precio_base) }}" data-id="{{ $producto->id }}" data-name="{{ $producto->nombre_producto }}" data-imagen="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="btn btn-md btn-cart addtocart" href="{{secure_url('cart/addtocart', [$producto->slug])}}" alt="Agregar al Carrito"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></a>

                                                @endif
                                                
                                        </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                        
                    @else
                    <div class="alert alert-danger">
                        <strong>Lo Sentimos!</strong> No Existen productos en esta categoría.
                    </div>
                @endif
            </div>
        </div>
        <!-- Fin Categoria 1 -->
        <!-- Categoria 2 -->
        <div class="row">
            <div class="col-md-3">   
                <div class="categoriaprod" id="cat2" style="background-image: url({{ secure_url('/').'/assets/img/categorias/lacteos.jpg' }});background-repeat: no-repeat;background-size: cover;">
                    <div class="layercat">
                        <div class="text-align:center;" id="contenido_list">
                            <h2>Lácteos</h2>
                            <a href="{{ secure_url('categoria/lacteos') }}" class="botones_cat boton_cat">VER TODOS</a>                                
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                @if(count($lacteos)>0)
                    @foreach($lacteos as $producto)
                        <div class="col-md-3 col-sm-6 col-xs-6">
                            <div class="productos">
                                <div class="text-align:center;">
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="img-responsive"></a>
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

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*(1-($precio[$producto->id]['precio']/100)),0,",",".") }}</span></p>
                                                    @break

                                                @case(3)

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;<span class="precio_base">${{ number_format($precio[$producto->id]['precio'],0,",",".") }}</span></p>
                                                    @break

                                                
                                            @endswitch

                                        @else

                                            <p id="precio_prod"><span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                        @endif
                                    

                                    @else

                                        <p id="precio_prod"><del class="@if($descuento==1) hidden @endif">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                    @endif
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="pum">{{ $producto->pum }}</h6></a>


                                    <div class="product_botones boton_{{ $producto->id }}">

                                    @if(isset($cart[$producto->slug]))

                                        <div class="row" style="margin-bottom:5px;">
                                        <div class="col-sm-10 col-sm-offset-1">
                                            <div class="input-group">
                                            <span class="input-group-btn">
                                                
                                                <button data-slug="{{ $producto->slug }}" data-tipo='resta' data-id="{{ $producto->id }}" class="btn btn-danger updatecart" type="button"><i class="fa fa-minus"></i></button>

                                            </span>

                                            <input id="cantidad_{{ $producto->id }}" name="cantidad_{{ $producto->id }}" type="number" step="1" readonly class="form-control" value="{{ $cart[$producto->slug]->cantidad }}" placeholder="">



                                            <span class="input-group-btn">

                                                <button data-slug="{{ $producto->slug }}" data-tipo='suma' data-id="{{ $producto->id }}" class="btn btn-success updatecart" type="button"><i class="fa fa-plus"></i></button>

                                                

                                            </span>

                                            </div><!-- /input-group -->
                                        </div><!-- /.col-lg-6 -->
                                        
                                        </div><!-- /.row -->

                                        <a class="btn btn-md btn-vermas2" href="{{ route('producto', [$producto->slug]) }}" style="margin-bottom:5px;">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>

                                    @else

                                        <a class="btn btn-md btn-vermas2" href="{{ route('producto', [$producto->slug]) }}">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>
                                        <a data-slug="{{ $producto->slug }}" data-price="{{ intval($producto->precio_base) }}" data-id="{{ $producto->id }}" data-name="{{ $producto->nombre_producto }}" data-imagen="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="btn btn-md btn-cart addtocart" href="{{secure_url('cart/addtocart', [$producto->slug])}}" alt="Agregar al Carrito"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></a>

                                    @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                        
                    @else
                    <div class="alert alert-danger">
                        <strong>Lo Sentimos!</strong> No Existen productos en esta categoría.
                    </div>
                @endif
            </div>
        </div>
        <!-- Fin Categoria 2 -->
         <!-- Categoria 3 -->
         <div class="row">
            <div class="col-md-3">   
                <div class="categoriaprod" id="cat3" style="background-image: url({{ secure_url('/').'/assets/img/categorias/quesos.jpg' }});background-repeat: no-repeat;background-size: cover;">
                    <div class="layercat">
                        <div class="text-align:center;" id="contenido_list">
                            <h2>Quesos</h2>
                            <a href="{{ secure_url('categoria/quesos') }}" class="botones_cat boton_cat">VER TODOS</a>                                
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                @if(count($quesos)>0)
                    @foreach($quesos as $producto)
                        <div class="col-md-3 col-sm-6 col-xs-6">
                            <div class="productos">
                                <div class="text-align:center;">
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="img-responsive"></a>
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

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*(1-($precio[$producto->id]['precio']/100)),0,",",".") }}</span></p>
                                                    @break

                                                @case(3)

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;<span class="precio_base">${{ number_format($precio[$producto->id]['precio'],0,",",".") }}</span></p>
                                                    @break

                                                
                                            @endswitch

                                        @else

                                            <p id="precio_prod"><span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                        @endif
                                    

                                    @else

                                        <p id="precio_prod"><del class="@if($descuento==1) hidden @endif">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                    @endif
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="pum">{{ $producto->pum }}</h6></a>

                                    <div class="product_botones boton_{{ $producto->id }}">

                                        @if(isset($cart[$producto->slug]))

                                            <div class="row" style="margin-bottom:5px;">
                                            <div class="col-sm-10 col-sm-offset-1">
                                                <div class="input-group">
                                                <span class="input-group-btn">
                                                    
                                                    <button data-slug="{{ $producto->slug }}" data-tipo='resta' data-id="{{ $producto->id }}" class="btn btn-danger updatecart" type="button"><i class="fa fa-minus"></i></button>

                                                </span>

                                                <input id="cantidad_{{ $producto->id }}" name="cantidad_{{ $producto->id }}" type="number" step="1" readonly class="form-control" value="{{ $cart[$producto->slug]->cantidad }}" placeholder="">


                                                <span class="input-group-btn">

                                                    <button data-slug="{{ $producto->slug }}" data-tipo='suma' data-id="{{ $producto->id }}" class="btn btn-success updatecart" type="button"><i class="fa fa-plus"></i></button>

                                                    

                                                </span>

                                                </div><!-- /input-group -->
                                            </div><!-- /.col-lg-6 -->
                                            
                                            </div><!-- /.row -->

                                            <a class="btn btn-md btn-vermas2" href="{{ route('producto', [$producto->slug]) }}" style="margin-bottom:5px;">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>

                                        @else

                                            <a class="btn btn-md btn-vermas2" href="{{ route('producto', [$producto->slug]) }}">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>
                                            <a data-slug="{{ $producto->slug }}" data-price="{{ intval($producto->precio_base) }}" data-id="{{ $producto->id }}" data-name="{{ $producto->nombre_producto }}" data-imagen="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="btn btn-md btn-cart addtocart" href="{{secure_url('cart/addtocart', [$producto->slug])}}" alt="Agregar al Carrito"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></a>

                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                        
                    @else
                    <div class="alert alert-danger">
                        <strong>Lo Sentimos!</strong> No Existen productos en esta categoría.
                    </div>
                @endif
            </div>
        </div>
        <!-- Fin Categoria 3 -->
         <!-- Categoria 4 -->
         <div class="row">
            <div class="col-md-3">   
                <div class="categoriaprod" id="cat4" style="background-image: url({{ secure_url('/').'/assets/img/categorias/postres.jpg' }});background-repeat: no-repeat;background-size: cover;">
                    <div class="layercat">
                        <div class="text-align:center;" id="contenido_list">
                            <h2>Postres y Dulces</h2>
                            <a href="{{ secure_url('categoria/postres-dulces') }}" class="botones_cat boton_cat">VER TODOS</a>                                
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                @if(count($postres)>0)
                    @foreach($postres as $producto)
                        <div class="col-md-3 col-sm-6 col-xs-6">
                            <div class="productos">
                                <div class="text-align:center;">
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="img-responsive"></a>
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

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*(1-($precio[$producto->id]['precio']/100)),0,",",".") }}</span></p>
                                                    @break

                                                @case(3)

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;<span class="precio_base">${{ number_format($precio[$producto->id]['precio'],0,",",".") }}</span></p>
                                                    @break

                                                
                                            @endswitch

                                        @else

                                            <p id="precio_prod"><span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                        @endif

                                    @else

                                        <p id="precio_prod"><del class="@if($descuento==1) hidden @endif">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                    @endif
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="pum">{{ $producto->pum }}</h6></a>

                                    <div class="product_botones boton_{{ $producto->id }}">

                                        @if(isset($cart[$producto->slug]))

                                            <div class="row" style="margin-bottom:5px;">
                                            <div class="col-sm-10 col-sm-offset-1">
                                                <div class="input-group">
                                                <span class="input-group-btn">
                                                    
                                                    <button data-slug="{{ $producto->slug }}" data-tipo='resta' data-id="{{ $producto->id }}" class="btn btn-danger updatecart" type="button"><i class="fa fa-minus"></i></button>

                                                </span>

                                                <input id="cantidad_{{ $producto->id }}" name="cantidad_{{ $producto->id }}" type="number" step="1" readonly class="form-control" value="{{ $cart[$producto->slug]->cantidad }}" placeholder="">


                                                <span class="input-group-btn">

                                                    

                                                    <button data-slug="{{ $producto->slug }}" data-tipo='suma' data-id="{{ $producto->id }}" class="btn btn-success updatecart" type="button"><i class="fa fa-plus"></i></button>

                                                </span>

                                                </div><!-- /input-group -->
                                            </div><!-- /.col-lg-6 -->
                                            
                                            </div><!-- /.row -->

                                            <a class="btn btn-md btn-vermas2" href="{{ route('producto', [$producto->slug]) }}" style="margin-bottom:5px;">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>

                                        @else

                                            <a class="btn btn-md btn-vermas2" href="{{ route('producto', [$producto->slug]) }}">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>
                                            <a data-slug="{{ $producto->slug }}" data-price="{{ intval($producto->precio_base) }}" data-id="{{ $producto->id }}" data-name="{{ $producto->nombre_producto }}" data-imagen="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="btn btn-md btn-cart addtocart" href="{{secure_url('cart/addtocart', [$producto->slug])}}" alt="Agregar al Carrito"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></a>

                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                        
                    @else
                    <div class="alert alert-danger">
                        <strong>Lo Sentimos!</strong> No Existen productos en esta categoría.
                    </div>
                @endif
            </div>
        </div>
        <!-- Fin Categoria 4 -->
        <!-- Categoria 5 -->
        <div class="row">
            <div class="col-md-3">   
                <div class="categoriaprod" id="cat5" style="background-image: url({{ secure_url('/').'/assets/img/categorias/esparcibles.jpg' }});background-repeat: no-repeat;background-size: cover;">
                    <div class="layercat">
                        <div class="text-align:center;" id="contenido_list">
                            <h2>Esparcibles e Ingredientes</h2>
                            <a href="{{ secure_url('categoria/esparcibles-ingredientes') }}" class="botones_cat boton_cat">VER TODOS</a>                                
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                @if(count($esparcibles)>0)
                    @foreach($esparcibles as $producto)
                        <div class="col-md-3 col-sm-6 col-xs-6">
                            <div class="productos">
                                <div class="text-align:center;">
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="img-responsive"></a>
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

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*(1-($precio[$producto->id]['precio']/100)),0,",",".") }}</span></p>
                                                    @break

                                                @case(3)

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;<span class="precio_base">${{ number_format($precio[$producto->id]['precio'],0,",",".") }}</span></p>
                                                    @break

                                                
                                            @endswitch

                                        @else

                                            <p id="precio_prod"><span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                        @endif

                                    @else

                                        <p id="precio_prod"><del class="@if($descuento==1) hidden @endif">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                    @endif
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="pum">{{ $producto->pum }}</h6></a>

                                    <div class="product_botones boton_{{ $producto->id }}">

                                        @if(isset($cart[$producto->slug]))

                                            <div class="row" style="margin-bottom:5px;">
                                            <div class="col-sm-10 col-sm-offset-1">
                                                <div class="input-group">
                                                <span class="input-group-btn">
                                                    
                                                    <button data-slug="{{ $producto->slug }}" data-tipo='resta' data-id="{{ $producto->id }}" class="btn btn-danger updatecart" type="button"><i class="fa fa-minus"></i></button>

                                                </span>

                                                <input id="cantidad_{{ $producto->id }}" name="cantidad_{{ $producto->id }}" type="number" step="1" readonly class="form-control" value="{{ $cart[$producto->slug]->cantidad }}" placeholder="">


                                                <span class="input-group-btn">

                                                    

                                                    <button data-slug="{{ $producto->slug }}" data-tipo='suma' data-id="{{ $producto->id }}" class="btn btn-success updatecart" type="button"><i class="fa fa-plus"></i></button>

                                                </span>

                                                </div><!-- /input-group -->
                                            </div><!-- /.col-lg-6 -->
                                            
                                            </div><!-- /.row -->

                                            <a class="btn btn-md btn-vermas2" href="{{ route('producto', [$producto->slug]) }}" style="margin-bottom:5px;">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>

                                        @else

                                            <a class="btn btn-md btn-vermas2" href="{{ route('producto', [$producto->slug]) }}">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>
                                            <a data-slug="{{ $producto->slug }}" data-price="{{ intval($producto->precio_base) }}" data-id="{{ $producto->id }}" data-name="{{ $producto->nombre_producto }}" data-imagen="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="btn btn-md btn-cart addtocart" href="{{secure_url('cart/addtocart', [$producto->slug])}}" alt="Agregar al Carrito"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></a>

                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                        
                    @else
                    <div class="alert alert-danger">
                        <strong>Lo Sentimos!</strong> No Existen productos en esta categoría.
                    </div>
                @endif
            </div>
        </div>
        <!-- Fin Categoria 5 -->
        <!-- Categoria 6 -->
        <div class="row">
            <div class="col-md-3">   
                <div class="categoriaprod" id="cat6" style="background-image: url({{ secure_url('/').'/assets/img/categorias/jugos.jpg' }});background-repeat: no-repeat;background-size: cover;">
                    <div class="layercat">
                        <div class="text-align:center;" id="contenido_list">
                            <h2>Bebidas de Fruta</h2>
                            <a href="{{ secure_url('categoria/bebidas-frutas') }}" class="botones_cat boton_cat">VER TODOS</a>                                
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                @if(count($bebidas)>0)
                    @foreach($bebidas as $producto)
                        <div class="col-md-3 col-sm-6 col-xs-6">
                            <div class="productos">
                                <div class="text-align:center;">
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="img-responsive"></a>
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

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*(1-($precio[$producto->id]['precio']/100)),0,",",".") }}</span></p>
                                                    @break

                                                @case(3)

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;<span class="precio_base">${{ number_format($precio[$producto->id]['precio'],0,",",".") }}</span></p>
                                                    @break

                                                
                                            @endswitch

                                        @else

                                            <p id="precio_prod"><span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                        @endif

                                    @else

                                        <p id="precio_prod"><del class="@if($descuento==1) hidden @endif">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                    @endif
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="pum">{{ $producto->pum }}</h6></a>

                                    <div class="product_botones boton_{{ $producto->id }}">

                                        @if(isset($cart[$producto->slug]))

                                            <div class="row" style="margin-bottom:5px;">
                                            <div class="col-sm-10 col-sm-offset-1">
                                                <div class="input-group">
                                                <span class="input-group-btn">
                                                    
                                                    <button data-slug="{{ $producto->slug }}" data-tipo='resta' data-id="{{ $producto->id }}" class="btn btn-danger updatecart" type="button"><i class="fa fa-minus"></i></button>

                                                </span>

                                                <input id="cantidad_{{ $producto->id }}" name="cantidad_{{ $producto->id }}" type="number" step="1" readonly class="form-control" value="{{ $cart[$producto->slug]->cantidad }}" placeholder="">


                                                <span class="input-group-btn">

                                                    <button data-slug="{{ $producto->slug }}" data-tipo='suma' data-id="{{ $producto->id }}" class="btn btn-success updatecart" type="button"><i class="fa fa-plus"></i></button>

                                                    

                                                </span>

                                                </div><!-- /input-group -->
                                            </div><!-- /.col-lg-6 -->
                                            
                                            </div><!-- /.row -->

                                            <a class="btn btn-md btn-vermas2" href="{{ route('producto', [$producto->slug]) }}" style="margin-bottom:5px;">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>

                                        @else

                                            <a class="btn btn-md btn-vermas2" href="{{ route('producto', [$producto->slug]) }}">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>
                                            <a data-slug="{{ $producto->slug }}" data-price="{{ intval($producto->precio_base) }}" data-id="{{ $producto->id }}" data-name="{{ $producto->nombre_producto }}" data-imagen="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="btn btn-md btn-cart addtocart" href="{{secure_url('cart/addtocart', [$producto->slug])}}" alt="Agregar al Carrito"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></a>

                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                        
                    @else
                    <div class="alert alert-danger">
                        <strong>Lo Sentimos!</strong> No Existen productos en esta categoría.
                    </div>
                @endif
            </div>
        </div>
        <!-- Fin Categoria 6 -->
        <!-- Categoria 7 -->
        <div class="row">
            <div class="col-md-3">   
                <div class="categoriaprod" id="cat7" style="background-image: url({{ secure_url('/').'/assets/img/categorias/finness.jpg' }});background-repeat: no-repeat;background-size: cover;">
                    <div class="layercat">
                        <div class="text-align:center;" id="contenido_list">
                            <h2>Línea Finesse</h2>
                            <a href="{{ secure_url('categoria/finesse') }}" class="botones_cat boton_cat">VER TODOS</a>                                
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                @if(count($finess)>0)
                    @foreach($finess as $producto)
                        <div class="col-md-3 col-sm-6 col-xs-6">
                            <div class="productos">
                                <div class="text-align:center;">
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="img-responsive"></a>
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

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*(1-($precio[$producto->id]['precio']/100)),0,",",".") }}</span></p>
                                                    @break

                                                @case(3)

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;<span class="precio_base">${{ number_format($precio[$producto->id]['precio'],0,",",".") }}</span></p>
                                                    @break
                                                
                                            @endswitch

                                        @else

                                            <p id="precio_prod"><span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                        @endif

                                    @else

                                        <p id="precio_prod"><del class="@if($descuento==1) hidden @endif">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                    @endif
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="pum">{{ $producto->pum }}</h6></a>

                                    <div class="product_botones boton_{{ $producto->id }}">

                                        @if(isset($cart[$producto->slug]))

                                            <div class="row" style="margin-bottom:5px;">
                                            <div class="col-sm-10 col-sm-offset-1">
                                                <div class="input-group">
                                                <span class="input-group-btn">
                                                    
                                                    <button data-slug="{{ $producto->slug }}" data-tipo='resta' data-id="{{ $producto->id }}" class="btn btn-danger updatecart" type="button"><i class="fa fa-minus"></i></button>

                                                </span>

                                                <input id="cantidad_{{ $producto->id }}" name="cantidad_{{ $producto->id }}" type="number" step="1" readonly class="form-control" value="{{ $cart[$producto->slug]->cantidad }}" placeholder="">


                                                <span class="input-group-btn">

                                                    <button data-slug="{{ $producto->slug }}" data-tipo='suma' data-id="{{ $producto->id }}" class="btn btn-success updatecart" type="button"><i class="fa fa-plus"></i></button>

                                                    

                                                </span>

                                                </div><!-- /input-group -->
                                            </div><!-- /.col-lg-6 -->
                                            
                                            </div><!-- /.row -->

                                            <a class="btn btn-md btn-vermas2" href="{{ route('producto', [$producto->slug]) }}" style="margin-bottom:5px;">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>

                                        @else

                                            <a class="btn btn-md btn-vermas2" href="{{ route('producto', [$producto->slug]) }}">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>
                                            <a data-slug="{{ $producto->slug }}" data-price="{{ intval($producto->precio_base) }}" data-id="{{ $producto->id }}" data-name="{{ $producto->nombre_producto }}" data-imagen="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="btn btn-md btn-cart addtocart" href="{{secure_url('cart/addtocart', [$producto->slug])}}" alt="Agregar al Carrito"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></a>

                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                        
                    @else
                    <div class="alert alert-danger">
                        <strong>Lo Sentimos!</strong> No Existen productos en esta categoría.
                    </div>
                @endif
            </div>
        </div>
        <!-- Fin Categoria 7 -->
        <!-- Categoria 8 -->
        <div class="row">
            <div class="col-md-3">   
                <div class="categoriaprod" id="cat8" style="background-image: url({{ secure_url('/').'/assets/img/categorias/baby.jpg' }});background-repeat: no-repeat;background-size: cover;">
                    <div class="layercat">
                        <div class="text-align:center;" id="contenido_list">
                            <h2>Alpina Baby</h2>
                            <a href="{{ secure_url('categoria/alpina-baby') }}" class="botones_cat boton_cat">VER TODOS</a>                                
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                @if(count($baby)>0)
                    @foreach($baby as $producto)
                        <div class="col-md-3 col-sm-6 col-xs-6">
                            <div class="productos">
                                <div class="text-align:center;">
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="img-responsive"></a>
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

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*(1-($precio[$producto->id]['precio']/100)),0,",",".") }}</span></p>
                                                    @break

                                                @case(3)

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;<span class="precio_base">${{ number_format($precio[$producto->id]['precio'],0,",",".") }}</span></p>
                                                    @break
                                                
                                            @endswitch

                                            <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="pum">{{ $precio[$producto->id]['pum'] }}</h6></a>

                                        @else

                                            <p id="precio_prod"><span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                            <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="pum">{{ $producto->pum }}</h6></a>

                                        @endif

                                    @else

                                        <p id="precio_prod"><del class="@if($descuento==1) hidden @endif">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                        <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="pum">{{ $producto->pum }}</h6></a>

                                    @endif
                                    

                                    <div class="product_botones boton_{{ $producto->id }}">

                                        @if(isset($cart[$producto->slug]))

                                            <div class="row" style="margin-bottom:5px;">
                                            <div class="col-sm-10 col-sm-offset-1">
                                                <div class="input-group">
                                                <span class="input-group-btn">
                                                    
                                                    <button data-slug="{{ $producto->slug }}" data-tipo='resta' data-id="{{ $producto->id }}" class="btn btn-danger updatecart" type="button"><i class="fa fa-minus"></i></button>

                                                </span>

                                                <input id="cantidad_{{ $producto->id }}" name="cantidad_{{ $producto->id }}" type="number" step="1" readonly class="form-control" value="{{ $cart[$producto->slug]->cantidad }}" placeholder="">


                                                <span class="input-group-btn">

                                                    <button data-slug="{{ $producto->slug }}" data-tipo='suma' data-id="{{ $producto->id }}" class="btn btn-success updatecart" type="button"><i class="fa fa-plus"></i></button>

                                                    

                                                </span>

                                                </div><!-- /input-group -->
                                            </div><!-- /.col-lg-6 -->
                                            
                                            </div><!-- /.row -->

                                            <a class="btn btn-md btn-vermas2" href="{{ route('producto', [$producto->slug]) }}" style="margin-bottom:5px;">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>

                                        @else

                                            <a class="btn btn-md btn-vermas2" href="{{ route('producto', [$producto->slug]) }}">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>
                                            <a data-slug="{{ $producto->slug }}" data-price="{{ intval($producto->precio_base) }}" data-id="{{ $producto->id }}" data-name="{{ $producto->nombre_producto }}" data-imagen="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="btn btn-md btn-cart addtocart" href="{{secure_url('cart/addtocart', [$producto->slug])}}" alt="Agregar al Carrito"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></a>

                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                        
                    @else
                    <div class="alert alert-danger">
                        <strong>Lo Sentimos!</strong> No Existen productos en esta categoría.
                    </div>
                @endif
            </div>
        </div>
        <!-- Fin Categoria 8 -->
        <!-- Categoria 9 -->
        <div class="row">
            <div class="col-md-3">   
                <div class="categoriaprod" id="cat9" style="background-image: url({{ secure_url('/').'/assets/img/categorias/no-lacteos.jpg' }});background-repeat: no-repeat;background-size: cover;">
                    <div class="layercat">
                        <div class="text-align:center;" id="contenido_list">
                            <h2>No Lácteos</h2>
                            <a href="{{ secure_url('categoria/no-lacteos') }}" class="botones_cat boton_cat">VER TODOS</a>                                
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                @if(count($nolacteos)>0)
                    @foreach($nolacteos as $producto)
                        <div class="col-md-3 col-sm-6 col-xs-6">
                            <div class="productos">
                                <div class="text-align:center;">
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="img-responsive"></a>
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

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*(1-($precio[$producto->id]['precio']/100)),0,",",".") }}</span></p>
                                                    @break

                                                @case(3)

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;<span class="precio_base">${{ number_format($precio[$producto->id]['precio'],0,",",".") }}</span></p>
                                                    @break
                                                
                                            @endswitch

                                        @else

                                            <p id="precio_prod"><span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                        @endif

                                    @else

                                        <p id="precio_prod"><del class="@if($descuento==1) hidden @endif">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                    @endif
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="pum">{{ $producto->pum }}</h6></a>


                                    <div class="product_botones boton_{{ $producto->id }}">

                                        @if(isset($cart[$producto->slug]))

                                            <div class="row" style="margin-bottom:5px;">
                                            <div class="col-sm-10 col-sm-offset-1">
                                                <div class="input-group">
                                                <span class="input-group-btn">
                                                    
                                                    <button data-slug="{{ $producto->slug }}" data-tipo='resta' data-id="{{ $producto->id }}" class="btn btn-danger updatecart" type="button"><i class="fa fa-minus"></i></button>

                                                </span>

                                                <input id="cantidad_{{ $producto->id }}" name="cantidad_{{ $producto->id }}" type="number" step="1" readonly class="form-control" value="{{ $cart[$producto->slug]->cantidad }}" placeholder="">


                                                <span class="input-group-btn">

                                                    

                                                    <button data-slug="{{ $producto->slug }}" data-tipo='suma' data-id="{{ $producto->id }}" class="btn btn-success updatecart" type="button"><i class="fa fa-plus"></i></button>

                                                </span>

                                                </div><!-- /input-group -->
                                            </div><!-- /.col-lg-6 -->
                                            
                                            </div><!-- /.row -->

                                            <a class="btn btn-md btn-vermas2" href="{{ route('producto', [$producto->slug]) }}" style="margin-bottom:5px;">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>

                                        @else

                                            <a class="btn btn-md btn-vermas2" href="{{ route('producto', [$producto->slug]) }}">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>
                                            <a data-slug="{{ $producto->slug }}" data-price="{{ intval($producto->precio_base) }}" data-id="{{ $producto->id }}" data-name="{{ $producto->nombre_producto }}" data-imagen="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="btn btn-md btn-cart addtocart" href="{{secure_url('cart/addtocart', [$producto->slug])}}" alt="Agregar al Carrito"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></a>

                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                        
                    @else
                    <div class="alert alert-danger">
                        <strong>Lo Sentimos!</strong> No Existen productos en esta categoría.
                    </div>
                @endif
            </div>
        </div>
        <!-- Fin Categoria 4 -->
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
@stop