    <div class="col-sm-8">
        
        @if(count($cart))
          
         <br>
            <div class="row">   
                <div class="">  
                        <h3>    Dirección de Envío </h3>
                 </div>
            </div>

             {!! Form::open(['url' => 'order/procesar', 'class' => 'form-horizontal', 'id' => 'procesarForm', 'name' => 'procesarForm', 'method'=>'POST']) !!}



            <div class="row direcciones" style="text-align: left;">

                <div class="col-sm-12">

                @if(isset($direcciones->id))

               
                
                    <div class=" ">

                    <input type="hidden" name="id_direccion"  id="id_direccion" value="{{ $direcciones->id }}" >  


                <div class=" ">
                    <div class="panel panel-default">
                        
                        <div class="panel-body">
                            <div class="box-body">
                                <dl class="dl-horizontal">

                                    <dt>Departamento</dt>
                                    <dd>{{ $direcciones->state_name }}</dd>

                                    <dt>Ciudad</dt>
                                    <dd>{{$direcciones->city_name }}</dd>

                                   
                                    <dt>Dirección</dt>
                                    <dd>
                                       {{ $direcciones->nombre_estructura.' '.$direcciones->principal_address.' - '.$direcciones->secundaria_address }}
                                    </dd>

                                    <dt>Apto, Puerta Interior</dt>
                                    <dd>
                                       {{ $direcciones->edificio_address.' '.$direcciones->detalle_address }}
                                    </dd>

                                    <dt>Barrio</dt>
                                    <dd>
                                       {{ $direcciones->barrio_address }}
                                    </dd>

                                    <dt>Notas</dt>
                                    <dd>{{ $direcciones->notas }}</dd>
                                    
                                </dl>
                            </div>
                            <!-- /.box-body -->
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

                </div>

                <!---<div class="row">

                <div class="col-sm-12" style="text-align: center;">

                    <button type="button" class="btn btn-raised btn-primary md-trigger addDireccionModal" data-toggle="modal" data-target="#modal-21">Agregar Nueva Direccion </button>

                </div>

                </div>-->

                <hr>

            <div class="row">

                    
                    @if(count($formasenvio))

                        <div class="">

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
                            
                        </div> <!-- End Col -->

                    @else

                        <div class="col-sm-12">

                            <h3>No hay Formas de envios para este grupo de usuarios</h3>

                        </div>  

                    @endif  <!-- End formas de pago -->


                    <!-- Empiezo formas de pagp -->


                    @if(count($formaspago))

                   

                        <div class="">

                             <div class="col-sm-12 ">

                                <h3>    Formas de pago</h3>

                                <input type="hidden" name="id_forma_pago" id="id_forma_pago" value="">

                                 <?php $c="checked"; ?>     

                                 <!-- Se construyen las opciones de envios -->                   

                                @foreach($formaspago as $fp)

                                    @if($fp->id==2)

                                        
                                   <!-- <a href="{{ $preference['response']['sandbox_init_point'] }}">-->

                                        <div data-href="{{ $preference['response']['sandbox_init_point'] }}" data-id={{ $fp->id }} class="row forma border pointer mercadopago ">
                                            
                                            <div class="col-sm-2 ">
                                                
                                                <i class="fa fa-money"></i>

                                            </div>
                                            <div class="col-sm-8 ">

                                                <img src="{{ url('/uploads/files/mercado-pago.jpg') }}">

                                                

                                            </div>

                                            <div class="col-sm-2 " style="text-align: right;">

                                                <i class="fa  fa-chevron-right"></i>

                                            </div>

                                        </div>
                                    <!--</a>-->

                                    @elseif($fp->id==4)

                                        <div  data-id={{ $fp->id }} class="row forma border pointer cupones">
                                            
                                            <div class="col-sm-2 ">
                                                
                                                <i class="fa fa-money"></i>

                                            </div>
                                            <div class="col-sm-8 ">

                                                {{ $fp->nombre_forma_pago.' , '.$fp->descripcion_forma_pago }}

                                            </div>

                                            <div class="col-sm-2 " style="text-align: right;">

                                                <i class="fa  fa-chevron-right"></i>

                                            </div>

                                        </div>

                                        @else

                                        <div data-id={{ $fp->id }} class="row forma border pointer procesar">
                                            
                                            <div class="col-sm-2 ">
                                                
                                                <i class="fa fa-money"></i>

                                            </div>
                                            <div class="col-sm-8 ">

                                                {{ $fp->nombre_forma_pago.' , '.$fp->descripcion_forma_pago }}

                                            </div>

                                            <div class="col-sm-2 " style="text-align: right;">

                                                <i class="fa  fa-chevron-right"></i>

                                            </div>

                                        </div>

                                        @endif

                                @endforeach 


                                
                            </div>
                            
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

                <div class="row" style="border:  1px solid rgba(0,0,0,0.1)">
                    
                    <div class="col-sm-12">
                        
                        @foreach($cart as $car)

                        <li style="list-style-type: none;">

                            <div class="row">

                                <div class="col-sm-2">

                                    <img width="60px" src="{{ url('/').'/uploads/productos/'.$car->imagen_producto }}">
                                </div>

                                <div class="col-sm-8">

                                    <p>{{ $car->nombre_producto }}</p>
                                    <p>{{ $car->cantidad }}</p>
                                </div>

                                <div class="col-sm-2">{{ $car->precio_oferta*$car->cantidad  }}</div>

                            </div>
                        </li>

                        @endforeach


                    </div>



                </div>

                <h3>Pagos </h3>

                <div class="row">
                    
                    <div class="col-sm-12">

                        @if(isset($pagos))

                            @foreach($pagos as $pago)

                                <li style="list-style-type: none;">

                                    {{ $pago->id }}
                                    
                                </li>


                            @endforeach

                            

                        @else

                        <li style="list-style-type: none;">

                            No Hay pagos Registrados
                            
                        </li>
                        @endif
                        

                    </div>
                </div>

                <h3>Total a Pagar </h3>

                <div class="row">
                    
                    <div class="col-sm-12">

                        @if(isset($total))

                            

                                <li style="list-style-type: none;">

                                    {{ $total }}
                                    
                                </li>

                        
                        @endif
                        

                    </div>
                </div>
                



            </div>

        <br>  

         <div class=" res_env">  </div>  

 @else


     <h1><span class="label label-primary">Tu Carrito de Compras está Vacio</span></h1>
        

     @endif