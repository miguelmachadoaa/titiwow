
@if(!isset($total_descuentos_icg))

@php

$total_descuentos_icg=0;

@endphp

@endif

@if(count($formaspago))

            @foreach($formaspago as $fpp)

                    @if(!count($pagos))

                    @if($fpp->id==4)

                    @if(isset($bono_disponible->total))

                    @if($bono_disponible->total>0)

                    <div class="row">

                      <div class="col-sm-12">@if (session('aviso_bono'))
                        <div class="alert alert-danger">
                            {{ session('aviso_bono') }}
                        </div>
                    @endif</div>

                      




                    <div class="col-sm-12">

                                  <h3 class="" style="margin-top: 1em;">
                                     <p>Saldo Disponible: COP {{number_format($bono_disponible->total,0,',','.') }}</p>  
                                    
                                  </h3>
                                <div >
                                       
                                        <p style="margin-bottom: 1em;">

                                            <label class="btn btn-primary active col-sm-6" style="border-color: #eee; background-color: #eee; color: #221d44 !important;">
                                              <input type="radio" name="options" id="pago_total" value="pago_total" > Pago Total 
                                            </label>
                                            <label class="btn btn-primary col-sm-6" style="background-color: #eee;  border-color: #eee; color: #221d44 !important;">
                                              <input  type="radio" name="options" id="pago_parcial" value="pago_parcial"> Pago Parcial
                                            </label>
                                          </p>

                                          <div class=" col-sm-6 col-xs-12 contenidobono hidden" style="margin-top: 1em;">


                                        <p> <span class="mensaje_abono_usar"> Usar :</span> 
                                        
                                        <input class=" " id="bono_use" name="bono_use" type="number" min="0" max="{{$bono_disponible->total}}" 
                                          @if($total>$bono_disponible->total)
                                            value="{{$bono_disponible->total}}" 
                                          @else
                                            value="{{round($total)}}"
                                          @endif
                                          step="1" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"  ></p>
                                         
                                          <input id="monto_total_compra_bono_icg" name="monto_total_compra_bono_icg" type="hidden" 
                                          @if($total>$bono_disponible->total)
                                            value="{{$bono_disponible->total}}" 
                                          @else
                                            value="{{round($total)}}"
                                          @endif
                                          step="1" >    

                                        </div> 

                                       <div data-idpago="{{ $fpp->id }}" data-type="bono" data-id="4" class=" col-sm-6 col-xs-12 procesar btnpg contenidobono hidden" style="padding: 0px; background-color: #221D44; color: #ffffff; cursor: pointer; margin-top: 1em; ">

                                          <h5 class="text-center btnbonoh">Aplicar Saldo  <i class="fa  fa-chevron-right"></i></h5>

                                      </div>

                                </div>


                    </div>

                    </div>

                            @endif
                            @endif






                    @endif
                    @endif  

                    @endforeach




            <div class="col-sm-12 ">

                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                 
                    <h3 >  Formas de pago</h3>

                        <input type="hidden" name="id_forma_pago" id="id_forma_pago" value="">

                    

                    <!-- Se construyen las opciones de envios -->

                    @foreach($formaspago as $fp)
        
                        @if($fp->id==2)

                        @if(isset($payment_methods['response']))

                        @foreach($payment_methods['response'] as $pm)

                            @if($pm['id']=='pse')

                                <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingPSE">
                                  <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsePSE" aria-expanded="true" aria-controls="collapsePSE">
                                     PSE - Tarjeta de Débito<span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
                                    </a>
                                  </h4>
                                </div>
                                <div id="collapsePSE" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingPSE">
                                  <div class="panel-body">

                                    <p class=" col-sm-6 col-xs-12">
                                        <img class="img-responsive" alt="PSE" style="width: 15em;     padding: 0.5em 0em 0em 0em;" src="../uploads/files/pse.jpg">
                                    </p>

                                    <div data-id="2" class="pse col-sm-6 col-xs-12  btnpg" style="padding:8px;background-color:#3DC639;color:#ffffff; cursor: pointer;">

                                        <h5 class="text-center">Pagar <i class="fa  fa-chevron-right"></i></h5>

                                    </div>

                                  </div>
                                </div>
                            </div>

                            @endif

                        @endforeach

                        @endif

                            

                            <br>

                             @if(isset($payment_methods['response']))

                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingTDC">
                                  <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTDC" aria-expanded="true" aria-controls="collapseTDC">
                                      Tarjeta de Credito <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
                                    </a>
                                  </h4>
                                </div>
                                <div id="collapseTDC" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingTDC">
                                  <div class="panel-body">

                                       <p class=" col-sm-6 col-xs-12">
                                        <img class="img-responsive" alt="PSE" style="width: 15em;     padding: 0.5em 0em 0em 0em;" src="../uploads/files/tdc.jpg">
                                        </p> 

                                     <div data-type='creditcard' id="creditcard" data-id="2" class=" col-sm-6 col-xs-12 btnpg" style="padding:8px;background-color:#3DC639;color:#ffffff; cursor: pointer;">

                                        <h5 class="text-center">Pagar <i class="fa  fa-chevron-right"></i></h5>

                                    </div>

                                  </div>
                                </div>
                            </div>


                            <br>

                         @if($almacen->mercadopago_sand=='1')


                                <form action="../order/creditcard" method="POST" class="form_creditcard">

                                  <script
                                   src="{{secure_url('assets/js/web-tokenize-checkout.js')}}"

                                    data-public-key="{{ $almacen->public_key_mercadopago_test }}"
                                    data-button-label="Pagar"
                                    data-transaction-amount="{{ (float)number_format($total-$total_pagos+$envio_base+$envio_impuesto-$total_descuentos_icg, 2, '.', '')}}"
                                  
                                    data-summary-product="{{ (float)number_format($total-$total_pagos+$envio_base+$envio_impuesto-$total_descuentos_icg, 2, '.', '') }}"
                                    data-summary-taxes="{{ (float)number_format($impuesto+$envio_impuesto, 2, '.', '') }}"
                                    >
                                  </script>
                                </form>


                                @endif

                                @if($almacen->mercadopago_sand=='2')


                                <form action="../order/creditcard" method="POST" class="form_creditcard">

                                  <script
                                    
                                    src="{{secure_url('assets/js/web-tokenize-checkout.js')}}"

                                    data-public-key="{{ $almacen->public_key_mercadopago }}"
                                    data-button-label="Pagar"
                                    data-transaction-amount="{{ (float)number_format($total-$total_pagos+$envio_base+$envio_impuesto-$total_descuentos_icg, 2, '.', '')}}"
                                  
                                    data-summary-product="{{ (float)number_format($total-$total_pagos+$envio_base+$envio_impuesto-$total_descuentos_icg, 2, '.', '') }}"
                                    data-summary-taxes="{{ (float)number_format($impuesto+$envio_impuesto, 2, '.', '') }}"
                                    >
                                  </script>
                                </form>

                                @endif


                                @endif


                           @if(isset($payment_methods['response'])) 


                        @foreach($payment_methods['response'] as $pm)

                            @if($pm['payment_type_id']=='ticket')

                            @if($pm['id']!='davivienda')

                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading{{ $pm['id'] }}">
                                  <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $pm['id'] }}" aria-expanded="true" aria-controls="collapse{{ $pm['id'] }}">
                                      {{ ucfirst ($pm['id']) }} <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
                                    </a>
                                  </h4>
                                </div>
                                <div id="collapse{{ $pm['id'] }}" class="panel-collapse collapse " role="tabpanel" aria-labelledby="heading{{ $pm['id'] }}">
                                  <div class="panel-body">


                                       <p class=" col-sm-6 col-xs-12">
                                        <img class="img-responsive" alt="PSE" style="width: 15em;     padding: 0.5em 0em 0em 0em;" src="../uploads/files/{{ $pm['id'] }}.jpg">
                                        </p> 

                                     <div data-idpago="{{ $pm['id'] }}" data-type="ticket" data-id="2" class=" col-sm-6 col-xs-12 procesar btnpg" style="padding:8px;background-color:#3DC639;color:#ffffff; cursor: pointer;">

                                        <h5 class="text-center">Pagar <i class="fa  fa-chevron-right"></i></h5>

                                    </div>

                                  </div>
                                </div>
                            </div>

                                <br>
                                
                            @endif
                            @endif
                         
                        @endforeach


                        @endif

                        <br>

                    @elseif($fp->id==4)

                   





                    @elseif($fp->id==5)

                        @if(isset($cupo_credito_icg))

                        @if($cupo_credito_icg>0)

                        @if($cupo_credito_icg>$total)

                       
                          <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading{{ $fp->id }}">
                                      <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $fp->id }}" aria-expanded="true" aria-controls="collapse{{ $fp->id }}">
                                          {{ $fp->nombre_forma_pago }} <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="collapse{{ $fp->id }}" class="panel-collapse collapse " role="tabpanel" aria-labelledby="heading{{ $fp->id }}">
                                      <div class="panel-body">


                                           <p class=" col-sm-6 col-xs-12">
                                            {{ $fp->nombre_forma_pago }}

                                            <br>

                                            <b>Disponible:</b>  {{$cupo_credito_icg}}

                                            </p> 

                                           @if($fp->id==5)

                                              @if($cupo_credito_icg>($total-$total_pagos+$costo_envio))

                                             <div data-type='formaspago'  data-id="{{ $fp->id }}" class=" col-sm-6 col-xs-12 procesar" style="padding:8px;background-color:#3DC639;color:#ffffff; cursor: pointer;">

                                              <h5 class="text-center">Completar Pedido <i class="fa  fa-chevron-right"></i></h5>

                                            </div>

                                            @else


                                              <div data-type='formaspago'  data-id="{{ $fp->id }}" class=" col-sm-6 col-xs-12 " style="padding:8px;background-color:#f3f3f3;color:#ffffff; cursor: pointer;">

                                                <h5 class="text-center">Saldo Insuficiente <i class="fa  fa-chevron-right"></i></h5>

                                              </div>



                                            @endif



                                           @else

                                           <div data-type='formaspago'  data-id="{{ $fp->id }}" class=" col-sm-6 col-xs-12 procesar btnpg" style="padding:8px;background-color:#3DC639;color:#ffffff; cursor: pointer;">

                                            <h5 class="text-center">Completar Pedido <i class="fa  fa-chevron-right"></i></h5>

                                        </div>


                                           @endif

                                         

                                      </div>
                                    </div>
                                </div>

                                <br>

                        @endif

                        @endif

                        @endif

                    @elseif($fp->id==6)

                    

        

                    <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading{{ $fp->id.'x' }}">
                                  <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $fp->id.'x' }}" aria-expanded="true" aria-controls="collapse{{ $fp->id.'x' }}">
                                      Epayco <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
                                    </a>
                                  </h4>
                                </div>
                                <div id="collapse{{ $fp->id.'x' }}" class="panel-collapse collapse in " role="tabpanel" aria-labelledby="heading{{ $fp->id.'x' }}">
                                  <div class="panel-body">

                                      <p class=" col-sm-6 col-xs-12">
                                          <img class="img-responsive" alt="Epayco" style="width: 5em;     padding: 0.5em 0em 0em 0em;" src="https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/logos/logo_epayco_200px.png">
                                          </p> 

                                       <div data-type='epayco' id="epayco" data-id="5" class=" col-sm-6 col-xs-12  btnpg cbtnpagar" style="padding:8px;background-color:#3c763d;color:#ffffff;cursor:pointer;">

                                          <h5 class="text-center">Pagar <i class="fa  fa-chevron-right"></i></h5>

                                      </div>


                                    <form>
                                        <script
                                            src="https://checkout.epayco.co/checkout.js"
                                            class="epayco-button"
                                            data-epayco-key="{{$almacen->epayco_public_key}}"
                                            data-epayco-amount="{{ doubleval($total+$costo_envio-$total_descuentos) }}"
                                            data-epayco-name="Compra  {{ $user->id.' '.$almacen->nombre_tienda}}"
                                            data-epayco-description="Compra {{$almacen->nombre_tienda}}"
                                            data-epayco-currency="cop"
                                            data-epayco-country="co"
                                            data-epayco-test="@if($almacen->epayco_sand==1){{'true'}} @else {{'false'}} @endif"
                                            data-epayco-external="false"
                                            data-epayco-response="{{secure_url('/epayco/respuesta')}}"
                                            data-epayco-confirmation="{{secure_url('/epayco/confirmacion')}}"
                                            data-epayco-extra1="{{$user->id}}"
                                            data-epayco-extra2="{{time()}}"
                                            data-epayco-email-billing="{{$user->email}}"
                                            data-epayco-name-billing="{{$user->first_name.' '.$user->last_name}}"
                                            data-epayco-type-doc-billing="{{'CC'}}"
                                            data-epayco-number-doc-billing="{{$user->documento}}"
                                            data-epayco-mobilephone-billing="{{$user->telefono}}"
                                            >
                                        </script>
                                    </form>
                                    

                                  </div>
                                </div>
                            </div>

                          
                            <br>  


                    @else

                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading{{ $fp->id }}">
                                  <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $fp->id }}" aria-expanded="true" aria-controls="collapse{{ $fp->id }}">
                                      {{ $fp->nombre_forma_pago }} <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
                                    </a>
                                  </h4>
                                </div>
                                <div id="collapse{{ $fp->id }}" class="panel-collapse collapse " role="tabpanel" aria-labelledby="heading{{ $fp->id }}">
                                  <div class="panel-body">


                                       <p class=" col-sm-6 col-xs-12">
                                        {{ $fp->nombre_forma_pago }}

                                        @if($fp->id==3)

                                        <br>


                                       <b>Disponible:</b>  {{$saldo[$user->id]}}

                                        @endif

                                        </p> 

                                       @if($fp->id==3)

                                          @if($saldo[$user->id]>($total-$total_pagos+$costo_envio))

                                         <div data-type='formaspago'  data-id="{{ $fp->id }}" class=" col-sm-6 col-xs-12 procesar" style="padding:8px;background-color:#3DC639;color:#ffffff; cursor: pointer;">

                                          <h5 class="text-center">Completar Pedido <i class="fa  fa-chevron-right"></i></h5>

                                        </div>

                                        @else


                                          <div data-type='formaspago'  data-id="{{ $fp->id }}" class=" col-sm-6 col-xs-12 " style="padding:8px;background-color:#f3f3f3;color:#ffffff; cursor: pointer;">

                                            <h5 class="text-center">Saldo Insuficiente <i class="fa  fa-chevron-right"></i></h5>

                                          </div>



                                        @endif



                                       @else

                                       <div data-type='formaspago'  data-id="{{ $fp->id }}" class=" col-sm-6 col-xs-12 procesar btnpg" style="padding:8px;background-color:#3DC639;color:#ffffff; cursor: pointer;">

                                        <h5 class="text-center">Completar Pedido <i class="fa  fa-chevron-right"></i></h5>

                                    </div>


                                       @endif

                                     

                                  </div>
                                </div>
                            </div>

                            <br>

                    @endif

                    @endforeach


                </div>

            </div> <!-- end collapse -->

            <br>

  @else

      <div class=" col-sm-12 col-xs-12">

          <h3>No hay Formas de pago para este grupo de usuarios</h3>

      </div> 

  @endif 


 