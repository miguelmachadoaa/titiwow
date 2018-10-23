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
            <div class="col-md-12 col-sm-12 wow slideInLeft" data-wow-duration="1.5s">
                <div class="row">
                    @if(!$categorias->isEmpty())
                        @foreach($categorias as $categoria)
                            <div class="col-md-4 col-sm-12 col-xs-12"  id="caja_categoria">
                                <div class="{{ $categoria->css_categoria }}">
                                    <div class="layercat">
                                        <div class="text-align:center;" id="contenido_cat">
                                            <h2>{{ $categoria->nombre_categoria }}</h2>
                                            <h5>{{ $categoria->descripcion_categoria }}</h5>
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
                    <h3>Productos Destacados</h3>
                </div>
                <div class="col-md-12 col-sm-12 ">
                    <div class="products">
                        <div class="row">
                        @if(!$productos->isEmpty())
                            @foreach($productos as $producto)
                                <div class="col-md-3 col-sm-6 col-xs-6 ">
                                    <div class="productos">
                                        <div class="text-align:center;">
                                            <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="img-responsive"></a>
                                        </div>
                                        <a href="{{ route('producto', [$producto->slug]) }}" ><h1>{{ $producto->nombre_producto }}</h1></a>
                                        <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="text-align:center;">{{ $producto->presentacion_producto }}</h6></a>
                                        <div class="product_info">
                                        @if($descuento==1)

                                            @if(isset($precio[$producto->id]))

                                                @switch($precio[$producto->id]['operacion'])

                                                    @case(1)

                                                        <p id="precio_prod"><span class="precio_base">${{ number_format($producto->precio_base*$descuento,2,",",".") }}</span></p>
                                                        
                                                        @break

                                                    @case(2)

                                                        <p id="precio_prod"><del class="">${{ number_format($producto->precio_base, 2) }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*(1-($precio[$producto->id]['precio']/100)),2,",",".") }}</span></p>
                                                        @break

                                                    @case(3)

                                                        <p id="precio_prod"><del class="">${{ number_format($producto->precio_base, 2) }}</del>&nbsp;<span class="precio_base">${{ number_format($precio[$producto->id]['precio'],2,",",".") }}</span></p>
                                                        @break

                                                    
                                                @endswitch

                                            @else

                                                <p id="precio_prod"><span class="precio_base">${{ number_format($producto->precio_base*$descuento,2,",",".") }}</span></p>

                                            @endif




                                            @else

                                            <p id="precio_prod"><del class="@if($descuento==1) hidden @endif">${{ number_format($producto->precio_base, 2)}}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*$descuento,2,",",".") }}</span></p>

                                            @endif
                                            <p class="product_botones">
                                                <a class="btn btn-success addtocart" href="{{url('cart/addtocart', [$producto->slug])}}">Agregar al carro</a>
                                                <a class="btn btn-primary" href="{{ route('producto', [$producto->slug]) }}">Ver Más</a>
                                            </p>
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
                    </div>
                </div>
            </div>
</div>
            <!-- //Seccion productos destacados Fin -->

    </div>
    <!-- //Container End -->






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
                    <a href="{{ url('order/detail') }}" class="btn  btn-info " >Proceder a Cancelar</a>
                </div>
            </div>
        </div>
    </div>

<!-- Modal Direccion -->


        <!-- Modal Direccion -->
    <div class="modal fade" id="ubicacionModal" role="dialog" aria-labelledby="modalLabeldanger">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-sucess">
                        <h4 class="modal-title" id="modalLabeldanger">Indicanos desde donde estas visitando nuestra tienda </h4>
                </div>
                
                <div class="modal-body ">
                     <form method="POST" action="{{url('formasenvio/storeciudad')}}" id="addCiuadadForm" name="addCiuadadForm" class="form-horizontal">

                        <input type="hidden" name="base" id="base" value="{{ url('/') }}">

                        <div class="form-group col-sm-12">
                                    <label for="select21" class="col-md-3 control-label">
                                        Departamento
                                    </label>
                                    <div class="col-md-8" >
                                        <select style="margin: 4px 0;" id="state_id" name="state_id" class="form-control ">
                                            <option value="">Seleccione</option>

                                         
                                                        
                                                        @foreach($states as $state)

                                                        <option value="{{ $state->id }}">
                                                                {{ $state->state_name}}</option>
                                                        @endforeach
                                                        
                                           
                                            
                                          
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12">
                                    <label for="select21" class="col-md-3 control-label">
                                        Ciudad
                                    </label>
                                    <div class="col-md-8" >
                                        <select style="margin: 4px 0;" id="city_id" name="city_id" class="form-control ">
                                            <option value="">Seleccione</option>
                                           
                                            
                                          
                                        </select>
                                    </div>
                                </div>

                                 </form>


                        
                </div>
                <div class="modal-footer">
                    <button type="button"  class="btn  btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button"   class="btn  btn-primary saveubicacion" >Aceptar</button>
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

        $(document).ready(function(){

            if (localStorage.getItem('ubicacion')) {

                ubicacion=JSON.parse(localStorage.getItem('ubicacion'));


                if (ubicacion.status=='true'){

                    $('#ubicacion_header').html(ubicacion.city_name+' '+ubicacion.state_name);

                }else{

                    $('#ubicacionModal').modal('show');

                }


            }else{

                $('#ubicacionModal').modal('show');
            }
            

        });

        $('#ubicacion_header').click(function(e){

            e.preventDefault();

            $('#ubicacionModal').modal('show');

        });


         $('.saveubicacion').click(function () {
    
            var $validator = $('#addCiuadadForm').data('bootstrapValidator').validate();

            if ($validator.isValid()) {

                base=$('#base').val();
                city_id=$('#city_id').val();


                $.ajax({
                    type: "POST",
                    data:{  city_id },
                    url: base+"/configuracion/verificarciudad",
                        
                    complete: function(datos){     

                            ubicacion=JSON.parse(datos.responseText);

                            localStorage.setItem('ubicacion', datos.responseText);

                             $('#ubicacionModal').modal('hide');


                             if (ubicacion.status) {

                                $('#ubicacion_header').html(ubicacion.city_name+' '+ubicacion.state_name);

                            }else{

                                $('#ubicacion_header').html();
                            }




                    }
                });


                //document.getElementById("addDireccionForm").submit();


            }

        });


        $('.addtocart').on('click', function(e){

            e.preventDefault();

            url=$(this).attr('href');

            $.get(url, {}, function(data) {

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

         $("#addCiuadadForm").bootstrapValidator({
            fields: {
                
                city_id: {
                    validators:{
                        notEmpty:{
                            message: 'Debe seleccionar una ciudad'
                        }
                    }
                }
            }
        });



         $('select[name="state_id"]').on('change', function() {
            
                var stateID = $(this).val();

                var base = $('#base').val();

                    if(stateID) {
                        $.ajax({
                            url: base+'/configuracion/cities/'+stateID,
                            type: "GET",
                            dataType: "json",
                            success:function(data) {

                                
                                $('select[name="city_id"]').empty();
                                $.each(data, function(key, value) {
                                    $('select[name="city_id"]').append('<option value="'+ key +'">'+ value +'</option>');
                                });

                            }
                        });
                    }else{
                        $('select[name="city_id"]').empty();
                    }
                });


    </script>

    <!--page level js ends-->
@stop
