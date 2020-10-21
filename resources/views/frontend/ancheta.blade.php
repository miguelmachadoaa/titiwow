@extends('layouts/default')

{{-- Page title --}}
@section('title')
{{ $producto->nombre_producto}} @parent
@stop
@section('meta_tags')


<link rel="canonical" href="{{$url}}" />
<meta property="og:title" content="{{ $producto->seo_titulo }}| Alpina GO!">
<meta property="og:description" content="{{ $producto->seo_descripcion }}">
<meta property="og:image" content="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" />
<meta property="og:url" content="{{$url}}" />
<meta name="description" content="{{$producto->seo_descripcion}}"/>

@if(isset($configuracion->cuenta_twitter))
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="{{'@'.$configuracion->cuenta_twitter}}">
<meta name="twitter:description" content="{{ $producto->seo_descripcion }}">
<meta name="twitter:title" content="{{ $producto->seo_titulo }}">
<meta name="twitter:image" content="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}">

@endif


@if(isset($producto->robots))

    @if($producto->robots==null)

    @else

        <meta name="robots" content="{{$producto->robots}}">
        
    @endif

@else

    <meta name="robots" content="index, follow">
    
@endif



<script type="application/ld+json">
      {
        "@context": "https://schema.org/",
        "@type": "Product",
        "name": "{{$producto->nombre_producto}}",
        "image": "{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}",
        
        "description": "{{$producto->descripcion_corta}}",
        "sku": "{{$producto->referencia_producto}}",
        "mpn": "{{$producto->referencia_producto_sap}}",
        "brand": {
          "@type": "Brand",
          "name": "{{$producto->nombre_marca}}"
        },
        "offers": {
          "@type": "AggregateOffer",
          "availability": "http://schema.org/InStock",
          "offerCount": "5",
          "lowPrice": "{{$producto->precio_oferta}}",
          "highPrice": "{{$producto->precio_base}}",
          "priceCurrency": "COP"
        }
      }
    </script>






@endsection

{{-- page level styles --}}
@section('header_styles')
    <!--page level css starts-->
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/cart.css') }}">
    
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/cart.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/tabbular.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/verticalform.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/bootstrap-rating/bootstrap-rating.css') }}">
    <!--end of page level css-->


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
                        @foreach($catprincipal as $catp)
                        <a href="{{ secure_url('categoria/'.$catp->categ_slug) }}" alt="Ver Categoría"> {{ $catp->nombre_categoria }}</a>
                        @endforeach
                </li>
                <li >
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>
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
                <div class="col-sm-4 col-md-4">
                    <div class="row">
                        <div class="product_wrapper">
                            <img src="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" data-zoom-image="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="img-responsive" alt="{{ $producto->nombre_producto}}" title="{{ $producto->nombre_producto}}"/>

                             @if($producto->tipo_producto=='1')

                                @if(isset($inventario[$producto->id]))

                                    @if($inventario[$producto->id]<=0)

                                        <img style="    position: absolute;    top: 9px;    left: 0em;    float: left;    width: 8em !important;    height: 8em !important;" class="" style="" src="{{ secure_url('/').'/uploads/files/agotado.png' }}" alt="">

                                    @endif

                                @endif

                            @else

                                @if(isset($combos[$producto->id]))

                                @else

                                        <!--img style="    position: absolute;    top: 9px;    left: 0em;    float: left;    width: 8em !important;    height: 8em !important;" class="" style="" src="{{ secure_url('/').'/uploads/files/agotado.png' }}" alt=""-->

                                @endif


                            @endif




                        </div>
                    </div>
                </div>
                <!--individual product description-->
                <div class="col-sm-8 col-md-8">
                    <h1 class="text-primary" id="titulo_single">{{ $producto->nombre_producto}} </h1>
                    <p class="descripcion">{{ $producto->descripcion_corta}}</p>
                    
                    <p class="">


                         @if($producto->tipo_producto=='2')

                                @if(isset($combos[$producto->id]))

                                <b style="color: #143473;">Incluye:</b>

                                    @foreach($combos[$producto->id] as $cp)

                                        <div class="col-xs-12" style=" @if($loop->last) {{'margin-bottom:10px;'}} @endif padding-left: 4em;">

                                            <h6><a target="_blank"  href="{{ route('producto', [$cp->slug]) }}" style="color: #143473; " ><i class="fa fa-angle-double-right"></i>{{$cp->cantidad.'- '.$cp->nombre_producto}}</a></h6>

                                        </div>  

                                    @endforeach

                                @endif


                            @endif





                        <b>Marca:</b> <a href="{{ secure_url('marcas/'.$producto->marca_slug) }}" >{{ $producto->nombre_marca}}</a> <br />
                        <b>Categorías:</b> 
                        @foreach ($categos as $cats)
                            @if($loop->last)
                            <a href="{{ secure_url('categoria/'.$cats->categ_slug) }}" >{{ $cats->nombre_categoria }}</a>.
                            @else
                            <a href="{{ secure_url('categoria/'.$cats->categ_slug) }}" >{{ $cats->nombre_categoria }}</a>,
                            @endif
                        @endforeach
                       <br />
                        <b>Presentación del Producto:</b> {{ $producto->presentacion_producto}}<br />
                        <b>Medida:</b> {{ $producto->medida}}<br />
                        <b>SKU:</b> {{ $producto->referencia_producto_sap}}<br />
                        <b>Referencia:</b> {{ $producto->referencia_producto}}<br />

                    
                    </p>

                    

                  

                            @if ($configuracion->explicacion_precios=='1')

                            <div class="col-sm-12" style="padding:0; margin:0;">
                        
                        <a href="#" target="_blank"><img src="{{secure_url('uploads/files/banner-750x100.jpg')}}" alt="banner"></a>

                    </div>

                            @endif


                    
                    
                </div>
            </div>
        </div>
        <!--item view end-->
        <!--item desciption start-->

        <div class="widget-body">
        <div class="row container">


            <div class="col-sm-12">
                
                <h1  class="text-primary tetx-center" id="titulo_single">Arma tu ancheta  </h1>

            </div>

                <div class="row">
                                <div class="rightab">
                                    <div class="tab-content">

                    @foreach($anchetas_categorias as $ac)

                    <div class="tab-pane @if($loop->index==0) active @endif  {{'tabpane'.$ac->id}}     " id="tab{{$loop->iteration}}" data-minima="{{$ac->cantidad_minima}}">

                        <br>
                        
                        <h3 style="margin-top: 1em;"><strong>Paso {{$loop->iteration}}  </strong> - Seleccione {{$ac->nombre_categoria}} <small>Debe seleccionar como minimo {{$ac->cantidad_minima}} productos</small></h3>

                            @foreach($ac->productos as $p)

                                @if(isset($inventario[$p->id]))

                                    @if($inventario[$p->id]>0)

                                        <div class="p{{$p->id}}">

                                            @include('frontend.pancheta')
                                            
                                        </div>


                                    @endif

                                @endif

                            @endforeach

                            <div class="clearfix"></div>

                            <div class="form-actions" style="margin-top: 1em;">
                                
                                <div class="row">
                                    
                                    <div class="col-sm-6 col-xs-6" style="text-align: left;">
                                        
                                        <ul class="pager1 wizard no-margin">
                                            @if($loop->index==0)

                                                <!--li class="previous disabled">
                                                    <a href="#tab{{$loop->iteration}}" 
                                                       data-cantidad="-1"
                                                        class="btn  btn-danger btnnetx s{{$ac->id}}"
                                                        > Anterior </a>
                                                </li-->

                                            @else

                                                <li class="previous ">
                                                    <a 
                                                    href="#tab{{$loop->iteration-1}}" 
                                                    class="btn  btn-danger btnnetx s{{$ac->id}}"
                                                    data-cantidad="-1"
                                                    > Anterior </a>
                                                </li>

                                            @endif
                                                
                                                
                                            </ul>
                                        </div>


                                         <div class="col-sm-6 col-xs-6" style="text-align: right;">

                                            <ul class="pager1 wizard no-margin">

                                                @if($loop->last)

                                                    <li class="next disabled">
                                                        <a 
                                                        class="btn  btn-primary finalizarAncheta "
                                                        > Finalizar Ancheta </a>
                                                    </li>


                                                @else

                                                     <li class="next">
                                                        <a 
                                                        data-id="{{$ac->id}}" 
                                                        href="#tab{{$loop->iteration+1}}" 
                                                        data-cantidad="{{$ac->cantidad_minima}}"
                                                        class="btn  btn-danger btnnetx s{{$ac->id}}"
                                                        > Siguiente </a>
                                                    </li>

                                                @endif
                                               
                                            </ul>

                                        </div>
                                    </div>


                                   

                                </div>

                            </div>
                                   
                        @endforeach

                    </div>

                </div>

            </div>



             <div class="row listaancheta container" style="text-align: right;">
                                        
                @include('frontend.listaancheta')

            </div>




            <div class="row">
                                        
                <div class="col-sm-12">
                    
                    <div class="errorcantidad">
                        

                    </div>

                </div>

            </div>



                

        
    </div>
    </div>


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
                        <a href="{{ secure_url('cart/show') }}" class="btn  btn-info " >Proceder a Pagar</a>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Direccion -->

<input type="hidden" name="single" id="single" value="1">

@include('frontend.includes.newcart')


@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!--page level js start-->
    <script type="text/javascript" src="{{ secure_asset('assets/vendors/bootstrap-rating/bootstrap-rating.js') }}"></script>


   
    <!--page level js start-->

    <script src="{{ secure_asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ secure_asset('assets/js/cart.js') }}"></script>

    <script>


        $('.finalizarAncheta').on('click', function(){

            $('.addtocartunaancheta').fadeIn();
        });


         $(document).on('click','.addtocartunaancheta', function(e){

            e.preventDefault();

            base=$('#base').val();

            imagen=base+'/uploads/files/loader.gif';

            id=$(this).data('id');

            datasingle=$(this).data('single');

            price=$(this).data('price')+$('.totalancheta').val();

            slug=$(this).data('slug');

            single=$('#single').val();

            url=$(this).attr('href');


            pimagen=$(this).data('pimagen');
            
            name=$(this).data('name');


            $('.boton_'+id+'').html('<img style="max-width:32px; max-height:32px;" src="'+imagen+'">');

            $.post(base+'/cart/agregarunaancheta', {price, slug, datasingle}, function(data) {

                $('.boton_'+id+'').html(data);

                $(location).attr('href',base+'/cart/show');

                if (data.indexOf("<!--") > -1) {

                        $('.addtocartTrigger').data('imagen', pimagen);
                        $('.addtocartTrigger').data('name', name);
                        $('.addtocartTrigger').data('slug', slug);
                        $('.addtocartTrigger').data('price', price);
                        $('.addtocartTrigger').data('id', id);

                        $('.addtocartTrigger').trigger('click');

                    }

                   if (single==1) {

                        $('.vermas').remove();
                    }




            });

        });




        $(document).on('click', '.btnnetx', function(e){

            $('.addtocartunaancheta').fadeOut();

            e.preventDefault();

            href=$(this).attr('href');

            id=$(this).data('id');

            cantidad=$(this).data('cantidad');

            seleccionados=$('.tabpane'+id+" .pseleccionado").toArray().length;

            if (cantidad <=seleccionados) {

                $('.active').removeClass('active');

                $(href).addClass('active');

                $('.errorcantidad').html('');


            }else{

                $('.errorcantidad').html('<div class="alert alert-danger">Desbes seleccionar al menos '+cantidad+' productos</div>');
            }

            

           
        });




        $('.anchetabtn').on('click', function(){
            id=$(this).data('id');

            $('.anchetapanel').fadeOut('fast', function() { });
            $('.'+id).fadeIn('fast', function() { });
        });



        jQuery(document).ready(function () {
            new WOW().init();
        });





        $(document).ready(function(){

            $('.addtocartunaancheta').fadeOut();

            base=$('#base').val();

             $.get(base+'/cart/totalancheta', function(data) {

                    $('.listaancheta').html(data);

                });
        });




        $(document).on('click','.addtocartancheta', function(e){

            e.preventDefault();

            base=$('#base').val();

            imagen=base+'/uploads/files/loader.gif';

            id=$(this).data('id');

            price=$(this).data('price');

            slug=$(this).data('slug');

            $.post(base+'/cart/addtocartancheta', {price, slug, id}, function(data) {

                $('.p'+id+'').html(data);


                $.get(base+'/cart/totalancheta', function(data) {

                    $('.listaancheta').html(data);

                });


            });

        });


        $(document).on('click','.deltocartancheta', function(e){

            e.preventDefault();

            base=$('#base').val();

            imagen=base+'/uploads/files/loader.gif';

            id=$(this).data('id');

            price=$(this).data('price');

            slug=$(this).data('slug');

            $.post(base+'/cart/deltocartancheta', {price, slug, id}, function(data) {

                $('.p'+id+'').html(data);

                $.get(base+'/cart/totalancheta', function(data) {

                    $('.listaancheta').html(data);

                });

            });

        });









    </script>

@stop
