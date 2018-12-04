    <div class="col-sm-8">
        
        @if(count($cart))
          
         <br>
            <div class="row">   
                <div class="">  
                        <h3>    Dirección de Envío </h3>
                 </div>
            </div>

             {!! Form::open(['url' => secure_url('order/procesar'), 'class' => 'form-horizontal', 'id' => 'procesarForm', 'name' => 'procesarForm', 'method'=>'POST']) !!}



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

                    <div class="">
                        
                        <h3>Debe agregar una dirección de envío  </h3>
                    
                    </div>

                @endif 

                    </div>

                </div>

                    <div class="  res_dir">  
                    </div>

            

                <!---<div class="row">

                <div class="col-sm-12" style="text-align: center;">

                    <button type="button" class="btn btn-raised btn-primary md-trigger addDireccionModal" data-toggle="modal" data-target="#modal-21">Agregar Nueva Direccion </button>

                </div>

                </div>-->

                <hr>

            <div class="row">

                    
                    @if(count($formasenvio))

                       

                             <div class=" col-sm-12">

                                <h3>    Formas de Envios</h3>

                                 <?php $c="checked"; ?>     

                                 <!-- Se construyen las opciones de envios -->                   

                                @foreach($formasenvio as $fe)

                                <div class="row forma">

                                    <div class="col-sm-3 border" >
                                        
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="id_forma_envio" class="custom-radio" id="id_forma_envio" value="{{ $fe->id }}" {{ $c }}>&nbsp; </label>
                                        </div>

                                    </div>

                                    <div class="col-sm-6 border">

                                        {{ $fe->nombre_forma_envios.' , '.$fe->descripcion_forma_envios }}

                                    </div>

                                    <div class="col-sm-3 border">
                                        
                                        $0

                                    </div>
                                    

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

                    @endif  <!-- End formas de pago -->


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

                <div data-id={{ $fp->id }} class="row forma border pointer procesar">


                    <div class="col-sm-8 col-xs-12">

                       <p>{{ $fp->nombre_forma_pago.' , '.$fp->descripcion_forma_pago }}</p> 

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

                    
                        <div class=" col-sm-12">
                            <h3>No hay Formas de pago para este grupo de usuarios</h3>
                        </div>  

                    @endif  

                   

                    <!-- End formas de pago -->

                </div>
                
            </div> <!-- end Row --><!-- col-sm-8 -->

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
                            

                            <div  data-id={{ $fp->id }} class="row forma border pointer cupones">


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

                            <div class="col-sm-8 col-xs-8">
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

        @else


            <h1><span class="label label-primary">Tu Carrito de Compras está Vacio</span></h1>
        

        @endif