<div class="col-sm-12" style="padding-botton: 1em; ">

                        <div class="row" style=" padding: 0em 0em; border:  1px solid rgba(0,0,0,0.1); border-radius: 0.5em;">
                        
                            <div class="col-sm-12" style="    ">
                                
                                <div class="col-sm-8 col-xs-8" >
                                    <h4>Subtotal</h4>
                                </div>
                                <div class="col-sm-4 col-xs-4">

                                    <h4 style="color:#1450C9;">{{ number_format($total-$total_pagos-$impuesto,0,",",".")}}</h4> 

                                </div>

                            </div>

                            <div class="col-sm-12" style="    border-top: 1px solid rgba(0,0,0,0.1);">
                                
                                <div class="col-sm-8 col-xs-8" >
                                    <h4>Impuesto</h4>
                                </div>
                                <div class="col-sm-4 col-xs-4">

                                    <h4 style="color:#1450C9;">{{ number_format($impuesto,0,",",".")}}</h4> 

                                </div>

                            </div>

                            <div class="col-sm-12" style="    border-top: 1px solid rgba(0,0,0,0.1);">
                                
                                <div class="col-sm-8 col-xs-8" >
                                    <h4>Envio</h4>
                                </div>
                                <div class="col-sm-4 col-xs-4">

                                    <h4 style="color:#1450C9;">Gratis</h4> 

                                </div>
                                
                            </div>

                            <div class=" text-center " style="display: none">

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

                                                                <h4 style="color:#1450C9;">{{   number_format($pago->monto_pago,0,",",".") }}</h4> 

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
                            <div class="col-sm-12" style="    border-top: 1px solid rgba(0,0,0,0.1);">

                                <div class="col-sm-8 col-xs-8" >
                                    <h4>Total a Pagar</h4>
                                </div>
                                <div class="col-sm-4 col-xs-4">

                                       <h4 style="color:#1450C9;">{{ number_format($total-$total_pagos,0,",",".")}}</h4> 

                                </div>

                            </div>

                        @endif

                        </div>

                    </div>
