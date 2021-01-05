
@if(isset($total_descuentos_icg))

@if($total_descuentos_icg>0)

@else

@if(isset($cupo_icg))

    @if($cupo_icg>0)

        <div class="col-sm-12">
    
            <div class=" text-center " style="margin-top: 1em;">

                <div class="col-sm-12 col-xs-12" style="padding: 1em;">

                    <div  data-id="4" class="row forma border pointer addDescuentoIcg">

                        <div class="col-sm-8 col-xs-12">

                           <p>Cupo de Descuento ICG de  {{number_format($total*($configuracion->porcentaje_icg/100),2)}} COP</p> 

                        </div>

                        <div class="col-sm-4 col-xs-12" style="padding:8px;background-color:#3c763d;color:#ffffff;">

                            <h5 class="text-center">Aplicar <i class="fa fa-chevron-right"></i></h5>

                        </div>

                    </div>

                </div>
                                       

            </div>


        </div>


    @else


     <!--div class="col-sm-12">
    
            <div class=" text-center " style="margin-top: 1em;">

                <div class="col-sm-12 col-xs-12" style="padding: 1em;">

                    <div  data-id="4" class="row forma border pointer cupones">

                        <div class="col-sm-8 col-xs-12">

                           <p>Su cupo Mensual se Agoto</p> 

                        </div>

                        <div class="col-sm-4 col-xs-12" style="padding:8px;background-color:#3c763d;color:#ffffff;">

                            <h5 class="text-center">Aplicar <i class="fa fa-chevron-right"></i></h5>

                        </div>

                    </div>

                </div>
                                       

            </div>


        </div-->


    @endif
    

@else


@endif
@endif
@endif













@if(count($formaspago))

            <div class="col-sm-12 ">

                <div class="panel-group" id="accordion-detalles" role="tablist" aria-multiselectable="true">

                    <div class="panel panel-default">

                        <div class="panel-heading" role="tab" id="headingDETALLE">

                            <h4 class="panel-title">

                                <a style="color: #22b14c;" role="button" data-toggle="collapse" data-parent="#accordion-detalles" href="#collapseDETALLE" aria-expanded="true" aria-controls="collapseDETALLE">
                                Mi Pedido <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
                                </a>

                            </h4>

                        </div>

                        <div id="collapseDETALLE" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingDETALLE">

                            <div class="panel-body">
                        
                                <div class="col-sm-12 col-xs-12">

                                        @foreach($cart as $car)

                                        @if(isset($car->nombre_producto))

                                        <li style="list-style-type: none;">

                                            <div class="row">

                                                <div class="hidden-xs col-sm-2 ">

                                                   <a href="{{ route('producto', [$car->slug]) }}"> <img width="50em" src="{{ secure_url('/').'/uploads/productos/60/'.$car->imagen_producto }}"  alt="{{ $car->nombre_producto }}" title="{{ $car->nombre_producto }}"></a>

                                                </div>

                                                <div class="col-xs-9 col-sm-8 ">

                                                    <a href="{{ route('producto', [$car->slug]) }}" ><p>{{ $car->nombre_producto.' X '.$car->cantidad }}</p></a>

                                                </div>

                                                <div class="col-xs-3 col-sm-2 ">{{  number_format($car->precio_oferta*$car->cantidad,0,",",".")  }} COP</div>

                                            </div>

                                        </li>

                                        @endif

                                        @endforeach

                                </div>

                            </div>

                        </div>

                    </div>

                    <h3>  Monto</h3>

                    @include('frontend.includes.detallescompra')
                    
                    <div class="clearfix"></div>
                   

                       

                    

                    <!-- Se construyen las opciones de envios -->

                 


                </div>

            </div> <!-- end collapse -->

            <br>

        @else

            <div class=" col-sm-12 col-xs-12">

                <h3>No hay Formas de pago para este grupo de usuarios</h3>

            </div> 

        @endif 