
@extends('layouts/default')

{{-- Page title --}}
@section('title')
@foreach($marcaname as $marca)
{{ $marca->nombre_marca }}
@endforeach @parent
@stop
@section('meta_tags')
@php 
    foreach($marcaname as $marca)
        $marcago = $marca->nombre_marca;
        $marcades = $marca->descripcion_marca
@endphp
<meta property="og:title" content="{{$marcago}} | Alpina Go!">
<meta property="og:description" content="{{$marcades}}">
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
                <li class="hidden-xs">
                    <a href="{{ route('home') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>Inicio
                    </a>
                </li>
                <li class="hidden-md hidden-lg">
                    <a href="{{ route('home') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>
                    </a>
                </li>
                <li>
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>
                    <a href="#">Marcas</a>
                </li>
                <li >
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>
                    @foreach($marcaname as $marca)
                        <a href="{{ $marca->slug }} ">{{ $marca->nombre_marca }} </a>
                    @endforeach
                   
                </li>
            </ol>
            
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container contain_body">
<div class="row">
<div class="col-md-3 hidden-xs" style="padding-right:30px">
@include('layouts.sidebar')
</div>
<div class="col-md-9">
    <div class="products">
        <div class="row">
        @if(!$productos->isEmpty())
            @foreach($productos as $producto)
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="productos">
                        <div class="text-align:center;">
                            <a href="{{ route('producto', [$producto->slug]) }}" ><img src="../uploads/productos/{{ $producto->imagen_producto }}" class="img-responsive"></a>
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

                                            <p id="precio_prod"><del class="@if($descuento==1) hidden @endif">${{ number_format($producto->precio_base,0,",",".")}}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                            <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="pum">{{ $producto->pum }}</h6></a> 

                                        @endif
  
                                    <div class="product_botones boton_{{ $producto->id }}">

                                        @if(isset($cart[$producto->slug]))

                                            <div class="row" style="margin-bottom:5px;">
                                            <div class="col-sm-10 col-sm-offset-1">
                                                <div class="input-group">
                                                <span class="input-group-btn">
                                                    
                                                    <button data-slug="{{ $producto->slug }}" data-tipo='suma' data-id="{{ $producto->id }}" class="btn btn-success updatecart" type="button"><i class="fa fa-plus"></i></button>

                                                </span>

                                                <input id="cantidad_{{ $producto->id }}" name="cantidad_{{ $producto->id }}" type="number" step="1" readonly class="form-control" value="{{ $cart[$producto->slug]->cantidad }}" placeholder="">


                                                <span class="input-group-btn">

                                                    <button data-slug="{{ $producto->slug }}" data-tipo='resta' data-id="{{ $producto->id }}" class="btn btn-danger updatecart" type="button"><i class="fa fa-minus"></i></button>

                                                </span>

                                                </div><!-- /input-group -->
                                            </div><!-- /.col-lg-6 -->
                                            
                                            </div><!-- /.row -->

                                            <a class="btn btn-md btn-vermas" href="{{ route('producto', [$producto->slug]) }}" style="margin-bottom:5px;">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>

                                        @else

                                            <a class="btn btn-md btn-vermas" href="{{ route('producto', [$producto->slug]) }}">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>
                                            <a data-slug="{{ $producto->slug }}" data-price="{{ $producto->precio_base }}" data-id="{{ $producto->id }}" data-name="{{ $producto->nombre_producto }}" data-imagen="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="btn btn-md btn-cart addtocart" href="{{secure_url('cart/addtocart', [$producto->slug])}}" alt="Agregar al Carrito"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></a>

                                        @endif

                                    </div>
                        </div>
                    </div>
                </div>
                @if ($loop->iteration % 4 == 0)
                    </div>
                    <div class="row">
                @endif
            @endforeach
        @else
            <div class="alert alert-danger">
                <strong>Lo Sentimos!</strong> No Existen productos en esta categoría.
            </div>
        @endif
        </div>
        <div class="row">
            <div class="col-md-3 text-left align-bottom">Productos {{($productos->currentpage()-1)*$productos->perpage()+1}} a {{$productos->currentpage()*$productos->perpage()}}
                de  {{$productos->total()}}
            </div>
            <div class="col-md-9 text-right">
                {{ $productos->links() }}
            </div>
        </div>
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