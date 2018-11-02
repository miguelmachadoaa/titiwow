@extends('layouts/default')

{{-- Page title --}}
@section('title')
Inicio @parent
@stop
@section('meta_tags')
<meta property="og:title" content="Inicio | AlpinaGo">
<meta property="og:description" content="Bienvenidos a AlpinaGo">
<meta property="og:robots" content="index, follow">
<meta property="og:revisit-after" content="3 days">
@endsection

{{-- page level styles --}}
@section('header_styles')
    <!--page level css starts-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/tabbular.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/animate/animate.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/jquery.circliful.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/owl_carousel/css/owl.carousel.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/owl_carousel/css/owl.theme.css') }}">

    <!--end of page level css-->
@stop

{{-- slider --}}
@section('top')
    <!--Carousel Start -->
    <div id="owl-demo" class="owl-carousel owl-theme">
        <div class="item"><img src="{{ asset('assets/images/slides/slider1.jpg') }}" alt="Alpina Go">
        </div>
        <div class="item"><img src="{{ asset('assets/images/slides/slider3.png') }}" alt="Frutas">
        </div>
    </div>
    <!-- //Carousel End -->
@stop

{{-- content --}}
@section('content')

   
    <!-- //Layout Section Start -->
    <!-- Seccion categoria Inicio -->
    <div class="container cont_categorias">
        <div class="row">
            <div class="col-md-12 col-sm-12 text-center">
                <h3 class="catego">Categorías</h3>
                <div class="separador"></div>
            </div>
            <div class="col-md-12 col-sm-12 wow slideInLeft" data-wow-duration="1.5s">
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
                <div class="col-md-12 col-sm-12 ">
                    <div class="products">
                        <div class="row">
                        @if(!$productos->isEmpty())

                       

                            @foreach($productos as $producto)


                                <div class="col-md-2 col-sm-6 col-xs-6 ">
                                    <div class="productos">
                                        <div class="text-align:center;">
                                            <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="img-responsive homi"></a>
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

                                            <p id="precio_prod"><del class="@if($descuento==1) hidden @endif">${{ number_format($producto->precio_base, 2)}}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                        @endif

                                            <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="pum">{{ $producto->pum }}</h6></a>

                                            <p class="product_botones">
                                                <a class="btn btn-sm btn-success addtocart" href="{{url('cart/addtocart', [$producto->slug])}}">Agregar al carro</a>
                                                <a class="btn btn-sm btn-primary" href="{{ route('producto', [$producto->slug]) }}">Ver Más</a>
                                            </p>
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
</div>
            <!-- //Seccion productos destacados Fin -->
            <!-- Seccion marcas Inicio -->
            <div class="container cont_categorias">
                <div class="row">
                    <div class="col-md-12 col-sm-12 text-center">
                        <h3 class="catego">Marcas</h3>
                        <div class="separador"></div>
                    </div>
                    <div class="col-md-12 col-sm-12 wow slideInLeft" data-wow-duration="1.5s"> 
                        <div class="row">
                            @if(!$marcas->isEmpty())
                                @foreach($marcas as $marca)
                                    <div class="col-md-2 col-sm-6 col-xs-6" >
                                        <div class="brands">
                                            <a href="{{ route('marcas', [$marca->slug]) }}" >
                                                    <img src="{{ url('/').'/uploads/marcas/'.$marca->imagen_marca }}" class="img-responsive" title="{{ $marca->nombre_marca }}" alt="{{ $marca->nombre_marca }}">
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
                    <a href="{{ url('order/detail') }}" class="btn  btn-info " >Proceder a Pagar</a>
                </div>
            </div>
        </div>
    </div>

<!-- Modal Direccion -->




    
@stop
{{-- footer scripts --}}
@section('footer_scripts')
    <!-- page level js starts-->
    <script type="text/javascript" src="{{ asset('assets/js/frontend/jquery.circliful.js') }}"></script>
      <script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>

    <script type="text/javascript" src="{{ asset('assets/vendors/wow/js/wow.min.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/owl_carousel/js/owl.carousel.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/frontend/carousel.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/frontend/index.js') }}"></script>

      <script>
        jQuery(document).ready(function () {
            new WOW().init();
        });


        $('.addtocart').on('click', function(e){

            e.preventDefault();

            url=$(this).attr('href');

            $.get(url, {}, function(data) {

                $('.cartcontenido').html(data);

                $('#detailCartModal').modal('show');

                $('#detalle_carro_front').html($('#modal_cantidad').val()+' '+'Items');

            });



        });


    </script>

    <!--page level js ends-->
@stop
