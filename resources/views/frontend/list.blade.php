
@extends('layouts/default')

{{-- Page title --}}
@section('title')
Productos @parent
@stop
@section('meta_tags')
<meta property="og:title" content="Productos | AlpinaGo">
<meta property="og:description" content="Productos de AlpinaGo">
<meta property="og:robots" content="index, follow">
<meta property="og:revisit-after" content="3 days">
@endsection

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/shopping.css') }}">
    <link href="{{ asset('assets/vendors/animate/animate.min.css') }}" rel="stylesheet" type="text/css"/>
@stop

{{-- breadcrumb --}}
@section('top')
    <div class="breadcum">
        <div class="container">
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('home') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#3d3d3d" data-hc="#3d3d3d"></i>Inicio
                    </a>
                </li>
                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#01bc8c" data-hc="#01bc8c"></i>
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
                <div class="categoriaprod" id="cat1" style="background-image: url(../../assets/img/categorias/leche.png);">
                    <div class="layercat">
                        <div class="text-align:center;" id="contenido_list">
                            <h2>Leche</h2>
                            <a href="{{ url('categoria/leche') }}" class="botones_cat boton_cat">VER TODOS</a>                                
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
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="img-responsive"></a>
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

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base, 2) }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*(1-($precio[$producto->id]['precio']/100)),0,",",".") }}</span></p>
                                                    @break

                                                @case(3)

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base, 2) }}</del>&nbsp;<span class="precio_base">${{ number_format($precio[$producto->id]['precio'],0,",",".") }}</span></p>
                                                    @break

                                                
                                            @endswitch

                                        @else

                                            <p id="precio_prod"><span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                        @endif


                                    

                                    @else

                                        <p id="precio_prod"><del class="@if($descuento==1) hidden @endif">${{ number_format($producto->precio_base, 2).' -'.$producto->operacion }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".").' -'.$producto->operacion }}</span></p>

                                    @endif
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="pum">{{ $producto->pum }}</h6></a>


                                    


                                    <p class="product_botones">
                                        <a class="btn btn-sm btn-success addtocart" href="{{url('cart/addtocart', [$producto->slug])}}">Agregar al carro</a>
                                        <a class="btn btn-sm btn-primary" href="{{ route('producto', [$producto->slug]) }}">Ver Más</a>
                                    </p>
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
                <div class="categoriaprod" id="cat2" style="background-image: url(../../assets/img/categorias/lacteos.jpg);">
                    <div class="layercat">
                        <div class="text-align:center;" id="contenido_list">
                            <h2>Lácteos</h2>
                            <a href="{{ url('categoria/lacteos') }}" class="botones_cat boton_cat">VER TODOS</a>                                
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
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="img-responsive"></a>
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

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base, 2) }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*(1-($precio[$producto->id]['precio']/100)),0,",",".") }}</span></p>
                                                    @break

                                                @case(3)

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base, 2) }}</del>&nbsp;<span class="precio_base">${{ number_format($precio[$producto->id]['precio'],0,",",".") }}</span></p>
                                                    @break

                                                
                                            @endswitch

                                        @else

                                            <p id="precio_prod"><span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                        @endif


                                    

                                    @else

                                        <p id="precio_prod"><del class="@if($descuento==1) hidden @endif">${{ number_format($producto->precio_base, 2).' -'.$producto->operacion }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".").' -'.$producto->operacion }}</span></p>

                                    @endif
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="pum">{{ $producto->pum }}</h6></a>


                                    


                                    <p class="product_botones">
                                        <a class="btn btn-sm btn-success addtocart" href="{{url('cart/addtocart', [$producto->slug])}}">Agregar al carro</a>
                                        <a class="btn btn-sm btn-primary" href="{{ route('producto', [$producto->slug]) }}">Ver Más</a>
                                    </p>
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
                <div class="categoriaprod" id="cat3" style="background-image: url(../../assets/img/categorias/quesos.jpg);">
                    <div class="layercat">
                        <div class="text-align:center;" id="contenido_list">
                            <h2>Quesos</h2>
                            <a href="{{ url('categoria/quesos') }}" class="botones_cat boton_cat">VER TODOS</a>                                
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
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="img-responsive"></a>
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

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base, 2) }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*(1-($precio[$producto->id]['precio']/100)),0,",",".") }}</span></p>
                                                    @break

                                                @case(3)

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base, 2) }}</del>&nbsp;<span class="precio_base">${{ number_format($precio[$producto->id]['precio'],0,",",".") }}</span></p>
                                                    @break

                                                
                                            @endswitch

                                        @else

                                            <p id="precio_prod"><span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                        @endif


                                    

                                    @else

                                        <p id="precio_prod"><del class="@if($descuento==1) hidden @endif">${{ number_format($producto->precio_base, 2).' -'.$producto->operacion }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".").' -'.$producto->operacion }}</span></p>

                                    @endif
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="pum">{{ $producto->pum }}</h6></a>


                                    


                                    <p class="product_botones">
                                        <a class="btn btn-sm btn-success addtocart" href="{{url('cart/addtocart', [$producto->slug])}}">Agregar al carro</a>
                                        <a class="btn btn-sm btn-primary" href="{{ route('producto', [$producto->slug]) }}">Ver Más</a>
                                    </p>
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
                <div class="categoriaprod" id="cat4" style="background-image: url(../../assets/img/categorias/postres.jpg);">
                    <div class="layercat">
                        <div class="text-align:center;" id="contenido_list">
                            <h2>Postres y Dulces</h2>
                            <a href="{{ url('categoria/postres-dulces') }}" class="botones_cat boton_cat">VER TODOS</a>                                
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
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="img-responsive"></a>
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

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base, 2) }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*(1-($precio[$producto->id]['precio']/100)),0,",",".") }}</span></p>
                                                    @break

                                                @case(3)

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base, 2) }}</del>&nbsp;<span class="precio_base">${{ number_format($precio[$producto->id]['precio'],0,",",".") }}</span></p>
                                                    @break

                                                
                                            @endswitch

                                        @else

                                            <p id="precio_prod"><span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                        @endif


                                    

                                    @else

                                        <p id="precio_prod"><del class="@if($descuento==1) hidden @endif">${{ number_format($producto->precio_base, 2).' -'.$producto->operacion }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".").' -'.$producto->operacion }}</span></p>

                                    @endif
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="pum">{{ $producto->pum }}</h6></a>


                                    


                                    <p class="product_botones">
                                        <a class="btn btn-sm btn-success addtocart" href="{{url('cart/addtocart', [$producto->slug])}}">Agregar al carro</a>
                                        <a class="btn btn-sm btn-primary" href="{{ route('producto', [$producto->slug]) }}">Ver Más</a>
                                    </p>
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
                <div class="categoriaprod" id="cat5" style="background-image: url(../../assets/img/categorias/esparcibles.jpg);">
                    <div class="layercat">
                        <div class="text-align:center;" id="contenido_list">
                            <h2>Esparcibles e Ingredientes</h2>
                            <a href="{{ url('categoria/esparcibles-ingredientes') }}" class="botones_cat boton_cat">VER TODOS</a>                                
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
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="img-responsive"></a>
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

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base, 2) }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*(1-($precio[$producto->id]['precio']/100)),0,",",".") }}</span></p>
                                                    @break

                                                @case(3)

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base, 2) }}</del>&nbsp;<span class="precio_base">${{ number_format($precio[$producto->id]['precio'],0,",",".") }}</span></p>
                                                    @break

                                                
                                            @endswitch

                                        @else

                                            <p id="precio_prod"><span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                        @endif


                                    

                                    @else

                                        <p id="precio_prod"><del class="@if($descuento==1) hidden @endif">${{ number_format($producto->precio_base, 2).' -'.$producto->operacion }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".").' -'.$producto->operacion }}</span></p>

                                    @endif
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="pum">{{ $producto->pum }}</h6></a>


                                    


                                    <p class="product_botones">
                                        <a class="btn btn-sm btn-success addtocart" href="{{url('cart/addtocart', [$producto->slug])}}">Agregar al carro</a>
                                        <a class="btn btn-sm btn-primary" href="{{ route('producto', [$producto->slug]) }}">Ver Más</a>
                                    </p>
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
                <div class="categoriaprod" id="cat6" style="background-image: url(../../assets/img/categorias/jugos.jpg);">
                    <div class="layercat">
                        <div class="text-align:center;" id="contenido_list">
                            <h2>Bebidas de Fruta</h2>
                            <a href="{{ url('categoria/bebidas-frutas') }}" class="botones_cat boton_cat">VER TODOS</a>                                
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
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="img-responsive"></a>
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

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base, 2) }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*(1-($precio[$producto->id]['precio']/100)),0,",",".") }}</span></p>
                                                    @break

                                                @case(3)

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base, 2) }}</del>&nbsp;<span class="precio_base">${{ number_format($precio[$producto->id]['precio'],0,",",".") }}</span></p>
                                                    @break

                                                
                                            @endswitch

                                        @else

                                            <p id="precio_prod"><span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                        @endif


                                    

                                    @else

                                        <p id="precio_prod"><del class="@if($descuento==1) hidden @endif">${{ number_format($producto->precio_base, 2).' -'.$producto->operacion }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".").' -'.$producto->operacion }}</span></p>

                                    @endif
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="pum">{{ $producto->pum }}</h6></a>


                                    


                                    <p class="product_botones">
                                        <a class="btn btn-sm btn-success addtocart" href="{{url('cart/addtocart', [$producto->slug])}}">Agregar al carro</a>
                                        <a class="btn btn-sm btn-primary" href="{{ route('producto', [$producto->slug]) }}">Ver Más</a>
                                    </p>
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
                <div class="categoriaprod" id="cat7" style="background-image: url(../../assets/img/categorias/finness.jpg);">
                    <div class="layercat">
                        <div class="text-align:center;" id="contenido_list">
                            <h2>Línea Finesse</h2>
                            <a href="{{ url('categoria/finesse') }}" class="botones_cat boton_cat">VER TODOS</a>                                
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
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="img-responsive"></a>
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

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base, 2) }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*(1-($precio[$producto->id]['precio']/100)),0,",",".") }}</span></p>
                                                    @break

                                                @case(3)

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base, 2) }}</del>&nbsp;<span class="precio_base">${{ number_format($precio[$producto->id]['precio'],0,",",".") }}</span></p>
                                                    @break

                                                
                                            @endswitch

                                        @else

                                            <p id="precio_prod"><span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                        @endif


                                    

                                    @else

                                        <p id="precio_prod"><del class="@if($descuento==1) hidden @endif">${{ number_format($producto->precio_base, 2).' -'.$producto->operacion }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".").' -'.$producto->operacion }}</span></p>

                                    @endif
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="pum">{{ $producto->pum }}</h6></a>


                                    


                                    <p class="product_botones">
                                        <a class="btn btn-sm btn-success addtocart" href="{{url('cart/addtocart', [$producto->slug])}}">Agregar al carro</a>
                                        <a class="btn btn-sm btn-primary" href="{{ route('producto', [$producto->slug]) }}">Ver Más</a>
                                    </p>
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
                <div class="categoriaprod" id="cat8" style="background-image: url(../../assets/img/categorias/baby.jpg);">
                    <div class="layercat">
                        <div class="text-align:center;" id="contenido_list">
                            <h2>Alpina Baby</h2>
                            <a href="{{ url('categoria/alpina-baby') }}" class="botones_cat boton_cat">VER TODOS</a>                                
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
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="img-responsive"></a>
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

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base, 2) }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*(1-($precio[$producto->id]['precio']/100)),0,",",".") }}</span></p>
                                                    @break

                                                @case(3)

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base, 2) }}</del>&nbsp;<span class="precio_base">${{ number_format($precio[$producto->id]['precio'],0,",",".") }}</span></p>
                                                    @break

                                                
                                            @endswitch

                                        @else

                                            <p id="precio_prod"><span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                        @endif


                                    

                                    @else

                                        <p id="precio_prod"><del class="@if($descuento==1) hidden @endif">${{ number_format($producto->precio_base, 2).' -'.$producto->operacion }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".").' -'.$producto->operacion }}</span></p>

                                    @endif
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="pum">{{ $producto->pum }}</h6></a>


                                    


                                    <p class="product_botones">
                                        <a class="btn btn-sm btn-success addtocart" href="{{url('cart/addtocart', [$producto->slug])}}">Agregar al carro</a>
                                        <a class="btn btn-sm btn-primary" href="{{ route('producto', [$producto->slug]) }}">Ver Más</a>
                                    </p>
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
                <div class="categoriaprod" id="cat9" style="background-image: url(../../assets/img/categorias/no-lacteos.jpg);">
                    <div class="layercat">
                        <div class="text-align:center;" id="contenido_list">
                            <h2>No Lácteos</h2>
                            <a href="{{ url('categoria/no-lacteos') }}" class="botones_cat boton_cat">VER TODOS</a>                                
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
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="img-responsive"></a>
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

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base, 2) }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*(1-($precio[$producto->id]['precio']/100)),0,",",".") }}</span></p>
                                                    @break

                                                @case(3)

                                                    <p id="precio_prod"><del class="">${{ number_format($producto->precio_base, 2) }}</del>&nbsp;<span class="precio_base">${{ number_format($precio[$producto->id]['precio'],0,",",".") }}</span></p>
                                                    @break

                                                
                                            @endswitch

                                        @else

                                            <p id="precio_prod"><span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                        @endif


                                    

                                    @else

                                        <p id="precio_prod"><del class="@if($descuento==1) hidden @endif">${{ number_format($producto->precio_base, 2).' -'.$producto->operacion }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".").' -'.$producto->operacion }}</span></p>

                                    @endif
                                    <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="pum">{{ $producto->pum }}</h6></a>


                                    


                                    <p class="product_botones">
                                        <a class="btn btn-sm btn-success addtocart" href="{{url('cart/addtocart', [$producto->slug])}}">Agregar al carro</a>
                                        <a class="btn btn-sm btn-primary" href="{{ route('producto', [$producto->slug]) }}">Ver Más</a>
                                    </p>
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
                        <h4 class="modal-title" id="modalLabeldanger">Producto Agregado a su carro de compras</h4>
                    </div>
                    <div class="modal-body cartcontenido">

                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-default" data-dismiss="modal">Continuar Comprando</button>
                        <a href="{{ url('cart/show') }}" class="btn  btn-info " >Proceder a Cancelar</a>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Direccion -->







@endsection

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>
    <script>
        jQuery(document).ready(function () {
            new WOW().init();
        });


        $('.addtocart').on('click', function(e){

            e.preventDefault();

            url=$(this).attr('href');

            

            $.get(url, {}, function(data) {

               /* if (data.resultado) {

                    $('#detalle_carro_front').html(data.contenido);
                         
                }*/

                $('.cartcontenido').html(data);

                $('#detailCartModal').modal('show');

                $('#detalle_carro_front').html($('#modal_cantidad').val()+' '+'Items');

            });

            $.get(url, {}, function(data) {

                if (data.resultado) {

                    $('#detalle_carro_front').html(data.contenido);
                         
                }

            });



        });


    </script>
@stop