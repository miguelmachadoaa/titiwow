@extends('layouts/default')

{{-- Page title --}}
@section('title')
Carrito de Compras 
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

<!-- modal css -->

    <link href="{{ secure_asset('assets/css/pages/advmodals.css') }}" rel="stylesheet"/>

     <!--<link href="{{ secure_asset('assets/vendors/modal/css/component.css') }}" rel="stylesheet"/>-->

    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/shopping.css') }}">
    
    <link href="{{ secure_asset('assets/vendors/animate/animate.min.css') }}" rel="stylesheet" type="text/css"/>


    <link href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />

    <link href="{{ secure_asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" />

    <style type="text/css">
        
        .border{
            border:1px solid rgba(0,0,0,0.1);
            height: 4em;
        }

        .forma{
            margin-bottom: 1em;
    line-height: 4em;
        }

        .pointer{
            cursor: pointer;
        }

        .dl-horizontal dd {
            margin-left: 110px;
        }

        .dl-horizontal dt {
            float: left;
            width: 100px;
            overflow: hidden;
            clear: left;
            text-align: right;
            text-overflow: ellipsis;
            white-space: nowrap;
        }


        button.mercadopago-button {
                background: transparent;
                width: 100%;
                height: 100%;
                font-size: 14px;
                margin: 0px;
                padding: 0px;
                font-family: 'PlutoBold';
        }



        div.overlay {
    display:        table;
    position:        fixed;
    top:            0;
    left:            0;
    width:            100%;
    height:            100%;
    z-index: 1;
}
div.overlay > div {
    display:        table-cell;
    width:            100%;
    height:            100%;
    background:        #ccc;
    opacity:        1.5;
    text-align:        center;
    vertical-align:    middle;
}



    </style>

@stop

{{-- breadcrumb --}}
@section('top')
    <div class="breadcum">
        <div class="container">
            <ol class="breadcrumb">
                <li>
                    <a href="{{ secure_url('/') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#3d3d3d" data-hc="#3d3d3d"></i>Inicio
                    </a>
                </li>
                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#01bc8c" data-hc="#01bc8c"></i>
                    <a href="#">Carrito de Compras</a>
                </li>

                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#01bc8c" data-hc="#01bc8c"></i>
                    <a href="{{secure_url('productos')}}">Checkout</a>
                </li>
            </ol>
           
        </div>
    </div>
@stop

{{-- Page content --}}
@section('content')

<div style="display:none;" class="overlay"><div>Procesando...</div></div>


<div class="container contain_body container_cart_detail ">

    <div class="col-sm-8">
        
        @if(count($cart))
          
         <br>

        <div class="row"> 

            <h3>    Dirección de Envío </h3>

        </div>

        <!--{!! Form::open(['url' => secure_url('order/procesar'), 'class' => 'form-horizontal', 'id' => 'procesarForm', 'name' => 'procesarForm', 'method'=>'POST']) !!}-->

            <div class="row direcciones" style="text-align: left;">

                <div class="col-sm-12">

                    @if(isset($direcciones->id))

                        <input type="hidden" name="id_direccion"  id="id_direccion" value="{{ $direcciones->id }}" >

                        <div class="panel">

                            <div class="panel-body">

                                  <div class="row text-center">
                                        <div class="col-md-3 col-md-offset-3">
                                            <h5>Departamento:</h5>
                                        </div>
                                        <div class="col-md-3">
                                            <h6>{{ $direcciones->state_name }}</h6>
                                        </div>
                                    </div>
                                    <div class="row text-center">
                                        <div class="col-md-3 col-md-offset-3">
                                            <h5>Ciudad:</h5>
                                        </div>
                                        <div class="col-md-3">
                                            <h6>{{ $direcciones->city_name }}</h6>
                                        </div>
                                    </div>
                                    <div class="row text-center">
                                        <div class="col-md-3 col-md-offset-3">
                                            <h5>Dirección:</h5>
                                        </div>
                                        <div class="col-md-3">
                                            <h6>{{ $direcciones->nombre_estructura.' '.$direcciones->principal_address .' #'. $direcciones->secundaria_address .'-'.$direcciones->edificio_address }}</h6>
                                        </div>
                                    </div>
                                    <div class="row text-center">
                                        <div class="col-md-3 col-md-offset-3">
                                            <h5>Información Adicional:</h5>
                                        </div>
                                        <div class="col-md-3">
                                            <h6>{{ $direcciones->detalle_address }}</h6>
                                        </div>
                                    </div>
                                    <div class="row text-center">
                                        <div class="col-md-3 col-md-offset-3">
                                            <h5>Barrio:</h5>
                                        </div>
                                        <div class="col-md-3">
                                            <h6>{{ $direcciones->barrio_address }}</h6>
                                        </div>
                                    </div>
                                    <div class="row text-center">
                                        <div class="col-md-3 col-md-offset-3">
                                            <h5>Notas:</h5>
                                        </div>
                                        <div class="col-md-3">
                                            <h6>{{ $direcciones->notas }}</h6>
                                        </div>
                                    </div>

                            </div>

                        </div>

                        <!-- Se construyen las opciones de envios -->

                    @else

                        <h3>Debe agregar una dirección de envío  </h3>

                    @endif

                </div>

            <div class="  res_dir"></div>

        </div>



        <div class="row">

            @if(count($formasenvio))

            <div class=" col-sm-12 col-xs-12">

                <h3>    Formas de Envios</h3>

                <?php $c="checked"; ?> 

                 @foreach($formasenvio as $fe)

                 <div class="row forma">

                    <div class="col-sm-3 col-xs-3 border" >

                        <div class="radio">

                            <label><input type="radio" name="id_forma_envio" class="custom-radio" id="id_forma_envio" value="{{ $fe->id }}" {{ $c }}>&nbsp; </label>

                        </div>

                    </div>

                    <div class="col-sm-6 col-xs-6 border">

                        <p>{{ $fe->nombre_forma_envios.' , '.$fe->descripcion_forma_envios }}</p>

                    </div>

                    <div class="col-sm-3 col-xs-3 border">  Gratis </div>

                </div>

                <?php $c=""; ?> 

                @endforeach

            </div> <!-- End form group -->
            <div class="col-sm-12">

            <h6>* Los pedidos que se realicen de lunes a viernes, entre las 8:00 am y 5:00 pm serán entregados al siguiente día; por su parte, los pedidos que se realicen después de las 5:00pm serán entregados dos (2) días después. Aquellos que se realicen los sábados antes de las 3:00 pm serán entregados el lunes siguiente y los que se hagan los sábados después de las 3:00 pm, domingos y lunes antes de las 7:00 am serán entregados el martes.</h6>

            </div>  

            @else

            <div class="col-sm-12">

                <h3>No hay Formas de envios para este grupo de usuarios</h3>

            </div> 

            @endif

            <!-- End formas de pago -->

            <!-- Empiezo formas de pagp -->

            @if(count($formaspago))

            <div class="col-sm-12 ">

                <h3>    Formas de pago</h3>

                <input type="hidden" name="id_forma_pago" id="id_forma_pago" value="">

                <?php $c="checked"; ?>

                <!-- Se construyen las opciones de envios -->

                @foreach($formaspago as $fp)

                @if($fp->id==2)

                <div data-href="@if($configuracion->mercadopago_sand==1){{ $preference['response']['sandbox_init_point'] }} @else {{ $preference['response']['init_point'] }} @endif" data-id={{ $fp->id }} class="row forma border pointer mercadopago ">

                    <div class=" col-sm-8 img-responsive" style="min-height: 1em;" class=" col-sm-8 ">

                        <img class="img-responsive" src="{{ secure_url('/uploads/files/mercado-pago.jpg') }}" >

                    </div>

                    <div class=" col-sm-4 " style="padding:8px;background-color:#3c763d;color:#ffffff;">

                        <h5 class="text-center">Pagar <i class="fa  fa-chevron-right"></i></h5>

                    </div>

                </div>
                <br>

                @elseif($fp->id==4)

                <!--div  data-id={{ $fp->id }} class="row forma border pointer cupones">


                    <div class="col-sm-8 col-xs-12">

                       <p>{{ $fp->nombre_forma_pago.' , '.$fp->descripcion_forma_pago }}</p> 

                    </div>

                    <div class="col-sm-4 col-xs-12" style="padding:8px;background-color:#3c763d;color:#ffffff;">

                        <h5 class="text-center">Pagar <i class="fa  fa-chevron-right"></i></h5>

                    </div>

                </div-->


                @else

                <div data-type='formaspago'  data-id="{{ $fp->id }}" class="row forma border pointer procesar">


                    <div class="col-sm-8 col-xs-12">

                       <p>{{ $fp->nombre_forma_pago.' , '.$fp->descripcion_forma_pago }}</p> 

                    </div>

                    <div class="col-sm-4 col-xs-12" style="padding:8px;background-color:#3c763d;color:#ffffff;">

                        <h5 class="text-center">Pagar <i class="fa  fa-chevron-right"></i></h5>

                    </div>

                </div>
                <br>

                @endif

                


                @endforeach

                <div data-type='creditcard' id="creditcard" data-id="2" class="row forma border pointer    ">


                    <div class="col-sm-8 col-xs-12">

                       <p>Tarjeta de Crédito </p> 

                    </div>

                    <div class="col-sm-4 col-xs-12" style="background-color:#3c763d;color:#ffffff;">

                       <form action="{{ secure_url('/order/creditcard') }}" method="POST">
                          <script
                            src="https://www.mercadopago.com.co/integrations/v1/web-tokenize-checkout.js"
                            data-public-key="{{ $configuracion->public_key_mercadopago }}"
                            data-button-label="Pagar"
                            data-transaction-amount="{{ $total }}"
                          
                            data-summary-product="{{ $total }}"
                            data-summary-taxes="{{ $impuesto }}"
                            >
                          </script>
                        </form>

                    </div>

                </div>



                <br>

                

                @foreach($payment_methods['response'] as $pm)

                    @if($pm['payment_type_id']=='ticket')

                    <div data-idpago="{{ $pm['id'] }}" data-type="ticket" data-id="2" class="row forma border pointer  procesar  ">

                        <div class="col-sm-8 col-xs-12">

                           {{ $pm['name'] }}  <img src="{{ $pm['secure_thumbnail'] }} ">
                          

                        </div>

                        <div class="col-sm-4 col-xs-12" style="padding:8px;background-color:#3c763d;color:#ffffff;">

                            <h5 class="text-center">Pagar <i class="fa  fa-chevron-right"></i></h5>

                        </div>

                    </div>

                    @endif
                 






                @endforeach

            </div>

            <br>

            @else

            <div class=" col-sm-12 col-xs-12">

                <h3>No hay Formas de pago para este grupo de usuarios</h3>

            </div> 

            @endif 

        </div>

    </div> <!-- end Row --><!-- col-sm-8 -->

    <br><br><br>

    <div class="col-sm-4">

        <br>

        <h3>    Detalle de Orden </h3>

        <div class="row" style=" padding: 1em 0em; border:  1px solid rgba(0,0,0,0.1)">

            <div class="col-sm-12 col-xs-12">

                @foreach($cart as $car)

                <li style="list-style-type: none;">

                    <div class="row">

                        <div class="hidden-xs col-sm-2 ">

                           <a href="{{ route('producto', [$car->slug]) }}"> <img width="3em" src="{{ secure_url('/').'/uploads/productos/'.$car->imagen_producto }}"></a>

                        </div>

                        <div class="col-xs-9 col-sm-8 ">

                            <a href="{{ route('producto', [$car->slug]) }}" ><p>{{ $car->nombre_producto.' X '.$car->cantidad }}</p></a>

                        </div>

                        <div class="col-xs-3 col-sm-2 ">{{  number_format($car->precio_oferta*$car->cantidad,0,",",".")  }} COP</div>

                    </div>

                </li>

                @endforeach

            </div>




        <hr>

        <hr />
            <div class=" text-center ">

                <div class="col-sm-12 col-xs-12">
                    

                    <div  data-id="4" class="row forma border pointer cupones">


                    <div class="col-sm-8 col-xs-12">

                       <p>Cupon de Descuento</p> 

                    </div>

                    <div class="col-sm-4 col-xs-12" style="padding:8px;background-color:#3c763d;color:#ffffff;">

                        <h5 class="text-center">Agregar <i class="fa fa-chevron-right"></i></h5>

                    </div>

                </div>


            </div>

                

            </div>



            @if(isset($pagos))

            <!--h4 style="margin-left: 1em;">Pagos </h4-->

                         <div class="row">

                            <div class="col-sm-12">

                                @foreach($pagos as $pago)

                                    <li style="list-style-type: none;">

                                    @if($pago->id_forma_pago=='4')

                                        
                                        <div class="row text-center">

                                            <div class="col-sm-8 col-xs-8">
                                                <h4>{{ json_decode($pago->json)->codigo_cupon }}</h4>
                                            </div>
                                            <div class="col-sm-4 col-xs-4">

                                                <h4 style="color:#143473;">{{   number_format($pago->monto_pago,0,",",".") }}</h4> 

                                             </div>

                                        </div>


                                    @else

                                    @endif

                                    </li>

                                @endforeach

                            </div>

                        </div>

                    @endif




        @if(isset($total))
            <hr />
            <div class="row text-center">

                <div class="col-sm-8 col-xs-8" >
                    <h4>Total a Pagar</h4>
                </div>
                <div class="col-sm-4 col-xs-4">

                       <h4 style="color:#143473;">{{ number_format($total-$total_pagos,0,",",".")}}</h4> 

                </div>

            </div>

        @endif

        </div>

    </div>

    <br> 

    <div class=" res_env">  </div> 

</div> <!-- Container  -->

@else

<h2><span class="label label-primary">Tu Carrito de Compras está Vacio</span></h2>

@endif

<hr>

<p style="text-align: center;">

    <a class="btn btn-danger" href="{{secure_url('/productos')}}">Cancelar <i class="fa fa-times" aria-hidden="true"></i></a>


</p>

<!--{!! Form::close() !!}-->

</div>



<!-- Modal Direccion -->
 <div class="modal fade" id="modalCupones" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Aplicar Cupon</h4>
                    </div>
                    <div class="modal-body">
                        
                        <form method="POST" action="{{secure_url('cart/storedir')}}" id="addCuponForm" name="addCuponForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">

                            {{ csrf_field() }}
                            <div class="row">

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Codigo Cupon</label>

                                    <div class="col-sm-8">
                                        <input style="margin: 4px 0;" id="codigo_cupon" name="codigo_cupon" type="text" placeholder="Codigo de Cupon" class="form-control">
                                    </div>
                                </div>



                            </div>

                            <div class="row resCupon" ></div>
                        </form>


                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn  btn-primary sendCupon" >Agregar</button>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Direccion -->
   
<!-- Modal Direccion -->
 <div class="modal fade" id="addDireccionModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Agregar Dirección</h4>
                    </div>
                    <div class="modal-body">
                        
                        <form method="POST" action="{{secure_url('cart/storedir')}}" id="addDireccionForm" name="addDireccionForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">

                            {{ csrf_field() }}
                            <div class="row">

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Nickname Dirección</label>

                                    <div class="col-sm-8">
                                        <input style="margin: 4px 0;" id="nickname_address" name="nickname_address" type="text" placeholder="Nickname Direccion" class="form-control">
                                    </div>
                                </div>

                               


                                <div class="form-group col-sm-12">
                                    <label for="select21" class="col-md-3 control-label">
                                        Pais
                                    </label>
                                    <div class="col-md-8" >
                                        <select style="margin: 4px 0;" id="country_id" name="country_id" class="form-control ">
                                            <option value="">Seleccione</option>
                                           
                                            @foreach($countries as $pais)
                                            <option value="{{ $pais->id }}"
                                                    @if($pais->id == old('country_id')) selected="selected" @endif >{{ $pais->country_name}}</option>
                                            @endforeach
                                          
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12">
                                    <label for="select21" class="col-md-3 control-label">
                                        Departamento
                                    </label>
                                    <div class="col-md-8" >
                                        <select style="margin: 4px 0;" id="state_id" name="state_id" class="form-control ">
                                            <option value="">Seleccione</option>
                                           
                                            
                                          
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



                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Calle </label>

                                    <div class="col-sm-8">
                                        <input style="margin: 4px 0;" id="calle_address" name="calle_address" type="text" placeholder="Calle" class="form-control">
                                    </div>
                                </div>
                                

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Código Postal</label>

                                    <div class="col-sm-8">
                                     <input style="margin: 4px 0;" id="codigo_postal_address" name="codigo_postal_address" type="text" placeholder="Codigo Postal" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Teléfono</label>

                                    <div class="col-sm-8">
                                        <input style="margin: 4px 0;" id="telefono_address" name="telefono_address" type="text" placeholder="Telefono" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Notas</label>

                                    <div class="col-sm-8">
                                        <textarea style="margin: 4px 0;" id="notas" name="notas" type="text" placeholder="Notas" class="form-control"></textarea>
                                    </div>
                                </div>


                            </div>
                        </form>


                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn  btn-primary sendDireccion" >Agregar</button>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Direccion -->


  






@endsection

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ secure_asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>

    <script language="javascript" type="text/javascript" src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}"></script>

    <script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>





    <script>


        /*funciones para cpones */

        $('body').on('click', '.cupones', function (){

            $('#modalCupones').modal('show');

       });


$('.sendCupon').click(function () {
    
    var $validator = $('#addCuponForm').data('bootstrapValidator').validate();

    if ($validator.isValid()) {

        codigo_cupon=$("#codigo_cupon").val();

        var base = $('#base').val();

        $.ajax({
            type: "POST",
            data:{codigo_cupon},

            url: base+"/cart/addcupon",
                
            complete: function(datos){     

                $(".container_cart_detail").html(datos.responseText);

                $('#modalCupones').modal('hide');

               $("#nickname_address").val('');
                $("#city_id").val('');
                $("#calle_address").val('');
                $("#calle2_address").val('');
                $("#codigo_postal_address").val('');
               $("#telefono_address").val('');
                $("#notas").val('');
            
            }
        });
        //document.getElementById("addDireccionForm").submit();
    }

});




        /*Funciones para cipones */



        $('body').on('click', '.procesar', function (){

            $('.overlay').fadeIn();


            id_direccion= $("#id_direccion").val(); 
            
            id_forma_envio=$("input[name='id_forma_envio']:checked").val(); 
            
            id_forma_pago=$(this).data('id');


            if (id_forma_envio==undefined || id_direccion==undefined || id_forma_pago==undefined) {

               // alert('Todos los capos son obligatorios');

                $('.res_env').html('<div class="alert alert-danger" role="alert">Todos los campos son obligatorios</div>');

                $('.overlay').fadeOut();


            }else{

                id_direccion= $("#id_direccion").val(); 
            
                id_forma_envio=$("input[name='id_forma_envio']:checked").val(); 
                    
                id_forma_pago=$(this).data('id');

                base=$('#base').val();

                if (id_forma_pago==2) {

                    type=$(this).data('type');
                    idpago=$(this).data('idpago');

                    if(type=="ticket"){

                         $.ajax({
                            type: "POST",
                            data:{id_forma_envio, id_direccion},

                            url: base+"/cart/verificarDireccion",
                                
                            complete: function(datos){     

                               if(datos.responseText=='true'){

                                    $('#procesarForm').submit();

                                    $.ajax({
                                        type: "POST",
                                        data:{id_direccion, id_forma_envio, id_forma_pago, type, idpago},

                                        url: base+"/order/procesarticket",
                                            
                                        complete: function(datos){     

                                            $('.contain_body').html(datos.responseText);

                                            $('.overlay').fadeOut();
                                        
                                        }

                                    });

                               }else{

                                    $('.res_env').html('<div class="alert alert-danger" role="alert">Esta ciudad no esta Disponible para envios.</div>');

                               }
                            
                            }
                        });

                    }else{








                    }

                   


                }else{



                       $.ajax({
                    type: "POST",
                    data:{id_forma_envio, id_direccion},
                    url: base+"/cart/verificarDireccion",
                        
                    complete: function(datos){     

                       if(datos.responseText=='true'){

                            //$('#procesarForm').submit();

                            $.ajax({
                                type: "POST",
                                data:{id_direccion, id_forma_envio, id_forma_pago},

                                url: base+"/order/procesar",
                                    
                                complete: function(datos){     

                                    $('.contain_body').html(datos.responseText);

                                    $('.overlay').fadeOut();
                                
                                }

                            });

                       }else{

                            $('.res_env').html('<div class="alert alert-danger" role="alert">Esta ciudad no esta Disponible para envios.</div>');

                       }
                    
                    }
                });



                }

             

            }

        });


        $('body').on('click', '.mercadopago', function (){



            id_direccion= $("#id_direccion").val(); 
            
            id_forma_envio=$("input[name='id_forma_envio']:checked").val(); 
            
            id_forma_pago=$(this).data('id');

            url=$(this).data('href');


            if (id_forma_envio==undefined || id_direccion==undefined || id_forma_pago==undefined) {

               // alert('Todos los capos son obligatorios');

                $('.res_env').html('<div class="alert alert-danger" role="alert">Todos los campos son obligatorios</div>');


            }else{

                $('#id_forma_pago').val(id_forma_pago);

                base=$('#base').val();

                $.ajax({
                    type: "POST",
                    data:{id_forma_envio, id_direccion, id_forma_pago},

                    url: base+"/cart/verificarDireccion",
                        
                    complete: function(datos){     

                       if(datos.responseText=='true'){

                            window.location.href = url;

                       }else{

                            $('.res_env').html('<div class="alert alert-danger" role="alert">Esta ciudad no esta Disponible para envios.</div>');

                       }
                    
                    }
                });

            }

        });



        $('body').on('click', '#creditcard', function (){



            id_direccion= $("#id_direccion").val(); 
            
            id_forma_envio=$("input[name='id_forma_envio']:checked").val(); 
            
            id_forma_pago=$(this).data('id');

            url=$(this).data('href');


            if (id_forma_envio==undefined || id_direccion==undefined || id_forma_pago==undefined) {

               // alert('Todos los capos son obligatorios');

                $('.res_env').html('<div class="alert alert-danger" role="alert">Todos los campos son obligatorios</div>');


            }else{

                $('#id_forma_pago').val(id_forma_pago);

                base=$('#base').val();

                $.ajax({
                    type: "POST",
                    data:{id_forma_envio, id_direccion, id_forma_pago},

                    url: base+"/cart/verificarDireccion",
                        
                    complete: function(datos){     

                       if(datos.responseText=='true'){

                            //window.location.href = url;

                       }else{

                            $('.res_env').html('<div class="alert alert-danger" role="alert">Esta ciudad no esta Disponible para envios.</div>');

                       }
                    
                    }
                });

            }

        });



        jQuery(document).ready(function () {
            new WOW().init();
        });

        $('.addDireccionModal').on('click', function(){
            $("#addDireccionModal").modal('show');
        });

       



    </script>

    <!-- modal js -->

    <script type="text/javascript" src="{{ secure_asset('assets/vendors/modal/js/classie.js')}}"></script>
    <script>
        $("#stack2,#stack3").on('hidden.bs.modal', function (e) {
            $('body').addClass('modal-open');
        });
    </script>

     <script type="text/javascript">

$("#addCuponForm").bootstrapValidator({
    fields: {
        codigo_cupon: {
            validators: {
                notEmpty: {
                    message: 'El Codigo es requerido '
                }
            },
            required: true,
            minlength: 3
        }
    }
});
        
        

$("#addDireccionForm").bootstrapValidator({
    fields: {
        nickname_address: {
            validators: {
                notEmpty: {
                    message: 'Nickname Direccion es Requerido'
                }
            },
            required: true,
            minlength: 3
        },
        calle_address: {
            validators: {
                notEmpty: {
                    message: 'Calle  es Requerido'
                    
                }
            },
            required: true,
            minlength: 3
        },
        
        telefono_address: {
            validators: {
                notEmpty: {
                    message: 'Telefono no puede esta vacion'
                }
            },
            minlength: 20
        },

        city_id: {
            validators:{
                notEmpty:{
                    message: 'Debe seleccionar una ciudad'
                }
            }
        }
    }
});



$('.sendDireccion').click(function () {
    
    var $validator = $('#addDireccionForm').data('bootstrapValidator').validate();

    if ($validator.isValid()) {

        nickname_address=$("#nickname_address").val();
        city_id=$("#city_id").val();
        calle_address=$("#calle_address").val();
        calle2_address=$("#calle2_address").val();
        codigo_postal_address=$("#codigo_postal_address").val();
        telefono_address=$("#telefono_address").val();
        notas=$("#notas").val();
        var base = $('#base').val();

        $.ajax({
            type: "POST",
            data:{nickname_address, city_id, calle_address, calle2_address, codigo_postal_address, telefono_address, notas},
            url: base+"/cart/storedir",
                
            complete: function(datos){     

                $(".direcciones").html(datos.responseText);

                $('#addDireccionModal').modal('hide');

               $("#nickname_address").val('');
                $("#city_id").val('');
                $("#calle_address").val('');
                $("#calle2_address").val('');
                $("#codigo_postal_address").val('');
               $("#telefono_address").val('');
                $("#notas").val('');
        
            
            }
        });


        //document.getElementById("addDireccionForm").submit();


    }

});
// $('#activate').on('ifChanged', function(event){
//     $('#commentForm').bootstrapValidator('revalidateField', $('#activate'));
// });
$('#addDireccionForm').keypress(
    function(event){
        if (event.which == '13') {
            event.preventDefault();
        }
    });



    $(document).ready(function(){
        $('.select2').select2({
            placeholder: "select",
            theme:"bootstrap"
        });
    })

    </script>



<script type="text/javascript">
        
            $(document).ready(function(){
        //Inicio select región
                $('select[name="country_id"]').on('change', function() {
                    $('select[name="city_id"]').empty();
                var countryID = $(this).val();
                var base = $('#base').val();
                    if(countryID) {
                        $.ajax({
                            url: base+'/configuracion/states/'+countryID,
                            type: "GET",
                            dataType: "json",
                            success:function(data) {

                                
                                $('select[name="state_id"]').empty();
                                $.each(data, function(key, value) {
                                    $('select[name="state_id"]').append('<option value="'+ key +'">'+ value +'</option>');
                                });

                            }
                        });
                    }else{
                        $('select[name="state_id"]').empty();
                    }
                });
            //fin select región

            //inicio select ciudad
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
            //fin select ciudad
        });


 


    </script>





@stop


@section('footer_scripts')
    
@stop