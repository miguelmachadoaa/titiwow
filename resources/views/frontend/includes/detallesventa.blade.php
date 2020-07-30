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