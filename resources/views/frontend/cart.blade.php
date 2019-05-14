
@extends('layouts/default')

{{-- Page title --}}
@section('title')
Carrito de Compras
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
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
                    <a href="#">Carrito de Compras</a>
                </li>
            </ol>
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container contain_body text-center" id="cartshow">

    <div class="row" id="table_detalle">

         

        @include('frontend.listcart')

        

    </div>

    <div class="row">
         
        <div class="col-sm-12">
             
            <div class="res"></div>

        </div>

    </div>



@if(isset($productos)) 



@if(isset($productos))
@if(!$productos->isEmpty())
<div class="row">
                <div class="col-md-12 col-sm-12 text-center">
                    <h3 class="catego">¿Un último antojo?</h3>
                    <div class="separador"></div>
                </div>
                <div class="col-md-12 col-sm-12 wow bounceInUp center" data-wow-duration="1.5s"> 
                    <div class="products">
                        <div class="row">
                        @if(!$productos->isEmpty())

                       

                            @foreach($prods as $producto)


                                <div class="col-md-2 col-sm-6 col-xs-6 ">
                                    <div class="productos">
                                        <div class="text-align:center;">
                                            <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="img-responsive homi"></a>
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


                                               


                                                @else
                                                  
                                                     
                                                     <a style="width: 90%;" data-slug="{{ $producto->slug }}" data-price="{{ intval($producto->precio_oferta) }}" data-id="{{ $producto->id }}" data-name="{{ $producto->nombre_producto }}" data-imagen="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="btn btn-md btn-cart addtocartDetail" href="{{secure_url('cart/addtocart', [$producto->slug])}}" alt="Agregar al Carrito"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></a>

                                                @endif


                                                
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                @if ($loop->iteration % 6 == 0)
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
                    </div>
                </div>
            </div>
 @endif
 @endif




@endif



















     
</div>





</div>
@endsection

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ secure_asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>
    <script>
        jQuery(document).ready(function () {
            new WOW().init();
        });


        $('body').on('click','.updatecartdetalle', function(e){

            e.preventDefault();

            base=$('#base').val();

            id=$(this).data('id');

            tipo=$(this).data('tipo');

            single=$('#single').val();


            slug=$(this).data('slug');

            cantidad=$('#cantidad_'+id+'').val();

            if (tipo=='suma') {

                cantidad=parseInt(cantidad);

                cantidad++;

            }else{

                cantidad=cantidad-1;
            }
            
                   $.post(base+'/cart/updatecartdetalle', {id, slug, cantidad}, function(data) {

                       
                         $('#table_detalle').html(data);


                    });

        });


         $(document).on('click','.addtocartDetail', function(e){

            $(this).fadeOut();

            e.preventDefault();

            base=$('#base').val();

            imagen=base+'/uploads/files/loader.gif';

            id=$(this).data('id');

            datasingle=$(this).data('single');

            price=$(this).data('price');

            slug=$(this).data('slug');

            single=$('#single').val();

            url=$(this).attr('href');

            //$('.boton_'+id+'').html('<img style="max-width:32px; max-height:32px;" src="'+imagen+'">');

            $.post(base+'/cart/agregardetail', {price, slug, datasingle}, function(data) {

                $('#table_detalle').html(data);


                       if (single==1) {

                            $('.vermas').remove();
                        }

            });

        });

    </script>
@stop