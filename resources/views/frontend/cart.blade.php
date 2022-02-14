
@extends('layouts/default')

{{-- Page title --}}
@section('title')
Carrito de Compras
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

 <meta property="og:title" content="{{ $configuracion->seo_titulo }} | Alpina GO!">

    <meta property="og:description" content="{{ $configuracion->seo_descripcion }}">

    <meta property="og:image" content="{{ $configuracion->seo_image }}" />

    @if(isset($url))

    <link rel="canonical" href="{{$url}}" />


    <meta property="og:url" content="{{$url}}" />

    @endif


    <meta name="description" content="{{$configuracion->seo_description}}"/>



    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/shopping.css') }}">
    <link href="{{ secure_asset('assets/vendors/animate/animate.min.css') }}" rel="stylesheet" type="text/css"/>
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
                    <a href="#">Carrito de Compras</a>
                </li>
            </ol>
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container contain_body text-center" id="cartshow">

    <div data-cart="{{json_encode($cart)}}"></div>

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

                            @if(isset($inventario[$producto->id]))

                                @if($inventario[$producto->id]>0)

                                <div class="col-md-2 col-sm-6 col-xs-6 ">
                                    <div class="productos">
                                        <div class="text-align:center;">
                                            <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" alt="{{ $producto->nombre_producto }}" title="{{ $producto->nombre_producto }}" class="img-responsive homi"></a>
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

                            @endif

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






<div class="row">
    <div class="col-sm-12" data-json="{{json_encode($cart)}}">
        
    </div>
</div>

</div>

</div>




<div class="modal fade" id="CartModal" role="dialog" aria-labelledby="modalLabeldanger">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">MI PEDIDO</h4>
              </div>
              <div class="modal-body bodycarrito">
                
              @if(is_array($cart))

                @foreach($cart as $key=>$cr)

                <div class="col-xs-12 " >

                    <div class="row productoscarritodetalle"  style="padding:0; margin:0;     border-bottom: 2px solid rgba(0,0,0,0.1);">
                        
                        <div class="col-sm-2" style="padding-top: 3%;">
                            <img style="width:100% ; max-width: 90px;" src="{{secure_url('uploads/productos/'.$cr->imagen_producto)}}"  alt="{{$cr->nombre_producto}}">
                        </div>
                        <div class="col-sm-4" style="padding-top: 3%;">
                            <p>{{$cr->nombre_producto}}</p>
                        </div>
                        
                        <div class="col-sm-2 col-xs-4" style="padding-top: 3%;">
                            <p>{{number_format($cr->precio_oferta, 0, ',', '.')}} </p>
                        </div>

                        <div class="col-sm-1 col-xs-1" style="padding-top: 3%;">
                            <p>{{$cr->cantidad}} </p>
                        </div>


                        <div class="col-sm-2 col-xs-4" style="padding-top: 3%; ">
                            <p>{{number_format($cr->precio_oferta*$cr->cantidad, 0, ',', '.')}} </p>
                        </div>

                        <div class="col-sm-1 col-xs-2" style="padding-left:0; padding-right:0; padding-top: 3%;     text-align: right; ">
                            <a data-id="{{ $cr->slug}}" data-slug="{{ $cr->slug}}"  href="#0" class="delete-item">
                                <img style="width:32px; padding-right:0; margin-bottom: 10px;" src="{{secure_url('assets/images/borrar.png')}}" alt="">
                            </a>
                        </div>

                    </div>

                </div>

                @endforeach

                @endif
              </div>
            
            </div><!-- /.modal-content -->

        </div>
    </div>



    

<div class="modal fade" id="modalLoginEmail" role="dialog" aria-labelledby="modalLabeldanger">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Ingresa tu email para continuar con la compra</h4>
              </div>

              <div class="modal-body bodylogin">
                    
                <div class="form-group {{ $errors->first('login_email', 'has-error') }}">
                    <label for="login_email" class="col-sm-2 control-label">
                        Ingrese su email 
                    </label>
                    <div class="col-sm-5">
                        <input type="text" id="login_email" name="login_email" class="form-control" placeholder="Ingrese su email"
                            value="">
                    </div>
                    <div class="col-sm-4">
                        {!! $errors->first('login_email', '<span class="help-block">:message</span> ') !!}
                    </div>
                </div>

            
              </div>

              <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary sendEmailLogin">Enviar</button>
                </div>
            
            </div><!-- /.modal-content -->

        </div>
    </div>




@endsection

{{-- page level scripts --}}
@section('footer_scripts')



    @if(isset($dl_productos))

    <script>

        window.dataLayer = window.dataLayer || [];

        window.dataLayer.push({
        'event': 'cartshow',
        'total': '{{ $total }}',
        'productos':{!!json_encode($dl_productos)!!}
        });

    </script>

    @endif



    <script src="{{ secure_asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>
    <script>
        jQuery(document).ready(function () {
            new WOW().init();
        });

        
        $('.btn-checkout').on('click', function(e){
            e.preventDefault();

            $('#modalLoginEmail').modal('show');


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

                      /* if (single==1) {

                            $('.vermas').remove();
                        }*/

            });

        });


         setInterval(function(){

            if ( $(".nodisponible").length ) {

                console.log('hay');
              
                $('.sendDetail').attr('disabled','')

            }else{

                $('.sendDetail').removeAttr('disabled')

            }

         }, 1000);

        

        
    </script>
@stop