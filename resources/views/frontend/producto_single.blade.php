@extends('layouts/default')

{{-- Page title --}}
@section('title')
{{ $producto->nombre_producto}}
@parent
@stop

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
                    <a href="{{ route('home') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#3d3d3d" data-hc="#3d3d3d"></i>Dashboard
                    </a>
                </li>
                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#01bc8c" data-hc="#01bc8c"></i>
                    <a href="#">{{ $producto->nombre_producto}}</a>
                </li>
            </ol>
            <div class="pull-right">
                <i class="livicon icon3" data-name="edit" data-size="20" data-loop="true" data-c="#3d3d3d" data-hc="#3d3d3d"></i> {{ $producto->nombre_producto}}
            </div>
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
    <!-- Container Section Start -->
    <div class="container">
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
                    <div class="producto_atributos">
                        <div class="row">
                                <div class="col-md-4 text-right">
                                    <h4>Cantidad</h4>
                                </div>
                                <div class="col-md-8">
                                    <input type="number" class="form-control" min="1" style="width:70px;">
                                </div>
                        </div>
                    </div>
                    <div class="box-info-product"> 
                        <div class="row">
                            <div class="col-md-4">
                            <div class="text-big3">
                                <del>$1000</del>
                            </div>
                            <div class="text-big4">$750</div>
                            <span>IVA incluido</span>
                            </div>
                            <div class="col-md-4">
                                <a href="#" class="btn btn-primary btn-lg text-white">Agregar al Carrito</a>        
                            </div>
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
        <div class="row">
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
        </div>
        <!--recently view item end-->
    </div>
    <!-- //Container Section End -->
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!--page level js start-->
    <script type="text/javascript" src="{{ asset('assets/js/frontend/elevatezoom.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/bootstrap-rating/bootstrap-rating.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/frontend/cart.js') }}"></script>
    <!--page level js start-->

@stop
