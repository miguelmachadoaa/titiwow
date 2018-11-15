@extends('layouts/default')

{{-- Page title --}}
@section('title')
{{ $producto->nombre_producto}} @parent
@stop
@section('meta_tags')
<meta property="og:title" content="{{ $producto->seo_titulo }} | AlpinaGo">
<meta property="og:description" content="{{ $producto->seo_descripcion }}">
<meta property="og:robots" content="index, follow">
<meta property="og:revisit-after" content="3 days">
@endsection

{{-- page level styles --}}
@section('header_styles')
    <!--page level css starts-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/cart.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/tabbular.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/bootstrap-rating/bootstrap-rating.css') }}">
    <!--end of page level css-->


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
                    <a href="#">Producto</a>
                </li>
                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#01bc8c" data-hc="#01bc8c"></i>
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
                <div class="col-md-4">
                    <div class="row">
                        <div class="product_wrapper">
                            <img id="zoom_09" src="{{ url('/').'/uploads/productos/'.$producto->imagen_producto }}" data-zoom-image="{{ url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="img-responsive" />
                        </div>
                    </div>
                </div>
                <!--individual product description-->
                <div class="col-md-8">
                    <h2 class="text-primary" id="titulo_single">{{ $producto->nombre_producto}} </h2>
                    <p class="descripcion">{{ $producto->descripcion_corta}}</p>
                    <p class="descripcion">
                        <b>Marca:</b> {{ $producto->nombre_marca}} <br />
                        <b>Categorías:</b> 
                        @foreach ($categos as $cats)
                            @if($loop->last)
                            <a href="{{ route('categoria', [$cats->categ_slug]) }}" >{{ $cats->nombre_categoria }}</a>.
                            @else
                            <a href="{{ route('categoria', [$cats->categ_slug]) }}" >{{ $cats->nombre_categoria }}</a>,
                            @endif
                        @endforeach
                       <br />
                        <b>Presentación del Producto:</b> {{ $producto->presentacion_producto}}<br />
                        <b>PUM:</b> 
                            @if(isset($precio[$producto->id]))

                               {{ $precio[$producto->id]['pum'] }}

                            @else

                             {{ $producto->pum}}


                            @endif


                        <br />
                        <b>Medida:</b> {{ $producto->medida}}<br />
                        <b>Referencia:</b> {{ $producto->referencia_producto}}<br />
                    </p>
                   <!-- <div class="producto_atributos">
                        <div class="row">
                                <div class="col-md-4 text-right">
                                    <h4>Cantidad</h4>
                                </div>
                                <div class="col-md-8">
                                    <input type="number" class="form-control" min="1" style="width:70px;">
                                </div>
                        </div>
                    </div>-->
                    <div class="box-info-product"> 
                        <div class="row">
                            <div class="col-md-4">
                            <div class="text-big3">
                               
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

                               </div>
                            <span class="span_impuesto">
                                @if($producto->id_impuesto == 1)
                                    IVA incluido
                                @elseif($producto->id_impuesto == 2)
                                    Excento de IVA
                                @endif
                            </span>
                            </div>

                                <div class="col-md-4">
                                    <div class="product_botones boton_{{ $producto->id }}">

                                                @if(isset($cart[$producto->slug]))

                                                    <div class="row">
                                                      <div class="col-sm-10 col-sm-offset-1">
                                                        <div class="input-group">
                                                          <span class="input-group-btn">
                                                            
                                                            <button data-slug="{{ $producto->slug }}" data-tipo='suma' data-id="{{ $producto->id }}" class="btn btn-success updatecart" type="button"><i class="fa fa-plus"></i></button>

                                                          </span>

                                                          <input id="cantidad_{{ $producto->id }}" name="cantidad_{{ $producto->id }}" type="number" step="1" readonly class="form-control" value="{{ $cart[$producto->slug]->cantidad }}" placeholder="">


                                                          <span class="input-group-btn">

                                                            <button data-slug="{{ $producto->slug }}" data-tipo='resta' data-id="{{ $producto->id }}" class="btn btn-success updatecart" type="button"><i class="fa fa-minus"></i></button>

                                                          </span>

                                                        </div><!-- /input-group -->
                                                      </div><!-- /.col-lg-6 -->
                                                     
                                                    </div><!-- /.row -->

                                                @else
                                                     
                                                        <a data-single="1" data-id="{{ $producto->id }}" class="btn btn-sm btn-success addtocart" href="{{url('cart/addtocart', [$producto->slug])}}">Agregar al carro</a>
                                                    
                                                

                                                @endif

                                                       

                                                
                                        </div>
                                    </div>


                            <!--<div class="col-md-4">
                                <a class="btn btn-success addtocart" href="{{url('cart/addtocart', [$producto->slug])}}">Añadir al Carrito</a>        
                            </div>-->
                        </div>
                    </div>
                    
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
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_default_1">
                                <p>{{ $producto->descripcion_larga}}</p>
                            </div>
                            
                            <!-- Tab-content End -->
                        </div>
                        <!-- //Tabbable-line End -->
                    </div>
                    <!-- Tabbable_panel End -->
                </div>
            </div>
        </div>
        <!--item desciption end-->
        <!--recently view item-->
        <!--div class="row">
            <h2 class="text-primary">Nuevos Productos</h2>
            <div class="divider"></div>
            <div class="flip-3d">
                <figure>
                    <img src="{{ asset('assets/images/cart/default/saree1.jpg') }}" alt="product image">
                    <figcaption>
                        <h4 class="text-white">Floral Printed Saree</h4>
                        <ul class="text-white">
                            <li>Product Type - Women's Saree</li>
                            <li>Color - Multi Colour</li>
                        </ul>
                        <h4 class="text-white"><del class="text-danger">Rs. 1599.00</del>  Rs. 1198.00   </h4>
                    </figcaption>
                </figure>
            </div>
            <div class="flip-3d">
                <figure>
                    <img src="{{ asset('assets/images/cart/default/shirt.jpg') }}" alt="product image">
                    <figcaption>
                        <h4 class="text-white">Atelier Check Shirt</h4>
                        <ul class="text-white">
                            <li>Product -Men's Club Wear</li>
                            <li>Care - Machine/Hand Wash</li>
                        </ulclass>
                        <h4 class="text-white"><del class="text-danger">Rs. 1999.00</del> Rs. 1499.00</h4>
                    </figcaption>
                </figure>
            </div>
            <div class="flip-3d">
                <figure>
                    <img src="{{ asset('assets/images/cart/default/sony.jpg') }}" alt="product image">
                    <figcaption>
                        <h4 class="text-white">Sony Xperia C3 - (Black)</h4>
                        <ul class="text-white">
                            <li>Android v4.4.2 (KitKat)</li>
                            <li>Quad-core 1.2 GHz</li>
                        </ul>
                        <h4 class="text-white"><del class="text-danger">Rs. 21,990</del>  Rs. 18,088</h4>
                    </figcaption>
                </figure>
            </div>
            <div class="flip-3d">
                <figure>
                    <img src="{{ asset('assets/images/cart/default/samsung.jpg') }}" alt="product image">
                    <figcaption>
                        <h4 class="text-white">Samsung Galaxy S6 64 GB - (White)</h4>
                        <ul class="text-white">
                            <li>Android v4.4.2 (KitKat)</li>
                            <li>Quad-core 1.2 GHz</li>
                        </ul>
                        <h4 class="text-white">Rs. 55,900</h4>
                    </figcaption>
                </figure>
            </div>
        </div-->
        <!--recently view item end-->
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
                        <a href="{{ url('cart/show') }}" class="btn  btn-info " >Proceder a Pagar</a>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Direccion -->

<input type="hidden" name="single" id="single" value="1">



<div class="cd-cart-container @if($cart!=NULL) @else empty @endif">
    <a href="#0" class="cd-cart-trigger">
        Carrito
        <ul class="count"> <!-- cart items count -->
            <li>@if($cart!=NULL) {{ count($cart) }}  @endif</li>
            <li>@if($cart!=NULL) {{ count($cart)+1 }} @endif</li>
        </ul> <!-- .count -->
    </a>

    <div class="cd-cart">
        <div class="wrapper">
            <header>
                <h2>Carrito</h2>
                <span class="undo">Item removed. <a href="#0">Undo</a></span>
            </header>
            
            <div class="body">
                <ul>
                    <!-- products added to the cart will be inserted here using JavaScript -->
                    @if(isset($cart))
                    @foreach($cart as $row)

                    <li class="product">
                        <div class="product-image">
                            <a href="#0">
                                <img src="{{ url('/').'/uploads/productos/'.$row->imagen_producto }}" alt="{{ $row->nombre_producto }}">
                            </a>
                        </div>
                        <div class="product-details">
                            <h3>
                                <a href="#0">{{ $row->nombre_producto }}</a>
                            </h3>
                            <span class="price">{{ $row->precio_oferta }}</span>
                            <div class="actions">
                                <a data-slug="{{ $row->slug }}" href="#0" class="delete-item">Borrar</a>
                                    <div class="quantity">
                                        <label for="cd-product-{{ $row->id }}">Cantidad</label>
                                            <span class="select">
                                                <select class="cartselect" data-slug="{{ $row->slug }}" id="cd-product-{{ $row->id }}" name="quantity">
                                                    <option value="{{ $row->cantidad }}">{{ $row->cantidad }}</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                </select>
                                            </span>
                                    </div>
                            </div>
                        </div>
                    </li>

                    @endforeach
                    @endif
                </ul>
            </div>

            <footer>
                <a href="#0" class="checkout btn"><em>Total - <span>@if(isset($cart)) {{ $total }}  @endif</span></em></a>
            </footer>
        </div>
    </div> <!-- .cd-cart -->
</div> <!-- cd-cart-container -->





@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!--page level js start-->
    <script type="text/javascript" src="{{ asset('assets/js/frontend/elevatezoom.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/bootstrap-rating/bootstrap-rating.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/frontend/cart.js') }}"></script>
    <!--page level js start-->

    <script src="{{ asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>
    <script>
        jQuery(document).ready(function () {
            new WOW().init();
        });





    </script>

@stop
