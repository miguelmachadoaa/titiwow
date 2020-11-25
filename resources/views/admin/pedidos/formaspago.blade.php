 <div class="col-sm-12">


          <div class="form-group {{ $errors->first('tomapedidos_terminos', 'has-error') }} checkbox">
              <label style="padding: 0;">

                  <input type="checkbox" name="tomapedidos_terminos" id="tomapedidos_terminos" value="1" require>  Acepto los <a href="{{ secure_url('paginas/terminos-condiciones')}}" class="menu-item" target="_blank" alt="Términos y Condiciones de Acceso a Alpina Go" title="Términos y Condiciones de Acceso a Alpina Go">Términos y Condiciones de Tomapedidos de Alpina Go.</a> 
              </label>
              {!! $errors->first('tomapedidos_terminos', '<span class="help-block">:message</span>') !!}
          </div>

          <p style="color: red;" class="error_tomapedidos_terminos"></p>
          

        </div>


        <div class="col-sm-12">


          <div class="form-group {{ $errors->first('tomapedidos_marketing', 'has-error') }} checkbox">
             <label style="padding: 0;">
                
                  <input type="checkbox" name="tomapedidos_marketing" id="tomapedidos_marketing" value="1" require>  Me gustaria recibir promociones de productos y servicios.
              </label>
              {!! $errors->first('tomapedidos_marketing', '<span class="help-block">:message</span>') !!}
          </div>

           <p style="color: red;" class="error_tomapedidos_marketing"></p>
          

        </div>



@if(count($formaspago))

            <div class="col-sm-12 ">

                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                 
                    <h3 >  Formas de pago</h3>

                        <input type="hidden" name="id_forma_pago" id="id_forma_pago" value="">

                    

                    <!-- Se construyen las opciones de envios -->

                    @foreach($formaspago as $fp)
        
                        @if($fp->id==2)

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
                                        <img class="img-responsive" alt="PSE" style="width: 15em;     padding: 0.5em 0em 0em 0em;" src="{{secure_url('/uploads/files/pse.jpg')}}">
                                    </p>

                                    <div data-id="2" class="pse col-sm-6 col-xs-12  btnpg" style="padding:8px;background-color:#3c763d;color:#ffffff; cursor: pointer;">

                                        <h5 class="text-center">Pagar <i class="fa  fa-chevron-right"></i></h5>

                                    </div>

                                  </div>
                                </div>
                            </div>

                            @endif

                        @endforeach

                            

                            <br>

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
                                        <img class="img-responsive" alt="PSE" style="width: 15em;     padding: 0.5em 0em 0em 0em;" src="{{secure_url('/uploads/files/tdc.jpg')}}">
                                        </p> 

                                     <div data-type='creditcard' id="creditcard" data-id="2" class=" col-sm-6 col-xs-12 btnpg" style="padding:8px;background-color:#3c763d;color:#ffffff; cursor: pointer;">

                                        <h5 class="text-center">Pagar <i class="fa  fa-chevron-right"></i></h5>

                                    </div>

                                  </div>
                                </div>
                            </div>


                            <br>

                         @if($configuracion->mercadopago_sand=='1')


                                <form action="../order/creditcard" method="POST" class="form_creditcard">

                                  <script
                                   src="{{secure_url('assets/js/web-tokenize-checkout.js')}}"

                                    data-public-key="{{ $configuracion->public_key_mercadopago_test }}"
                                    data-button-label="Pagar"
                                    data-transaction-amount="{{ (float)number_format($total-$total_pagos+$envio_base+$envio_impuesto, 2, '.', '')}}"
                                  
                                    data-summary-product="{{ (float)number_format($total-$total_pagos+$envio_base+$envio_impuesto, 2, '.', '') }}"
                                    data-summary-taxes="{{ (float)number_format($impuesto+$envio_impuesto, 2, '.', '') }}"
                                    >
                                  </script>
                                </form>


                                @endif

                                @if($configuracion->mercadopago_sand=='2')


                                <form action="../order/creditcard" method="POST" class="form_creditcard">

                                  <script
                                    
                                    src="{{secure_url('assets/js/web-tokenize-checkout.js')}}"

                                    data-public-key="{{ $configuracion->public_key_mercadopago }}"
                                    data-button-label="Pagar"
                                    data-transaction-amount="{{ (float)number_format($total-$total_pagos+$envio_base+$envio_impuesto, 2, '.', '')}}"
                                  
                                    data-summary-product="{{ (float)number_format($total-$total_pagos+$envio_base+$envio_impuesto, 2, '.', '') }}"
                                    data-summary-taxes="{{ (float)number_format($impuesto+$envio_impuesto, 2, '.', '') }}"
                                    >
                                  </script>
                                </form>

                                @endif


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
                                        <img class="img-responsive" alt="PSE" style="width: 15em;     padding: 0.5em 0em 0em 0em;" src="{{secure_url('/uploads/files').'/'.$pm['id'].'.jpg' }}">
                                        </p> 

                                     <div data-idpago="{{ $pm['id'] }}" data-type="ticket" data-id="2" class=" col-sm-6 col-xs-12 procesar btnpg" style="padding:8px;background-color:#3c763d;color:#ffffff; cursor: pointer;">

                                        <h5 class="text-center">Pagar <i class="fa  fa-chevron-right"></i></h5>

                                    </div>

                                  </div>
                                </div>
                            </div>

                                <br>
                                
                            @endif
                            @endif
                         
                        @endforeach

                        <br>

                    @elseif($fp->id==4)


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

                                         <div data-type='formaspago'  data-id="{{ $fp->id }}" class=" col-sm-6 col-xs-12 procesar" style="padding:8px;background-color:#3c763d;color:#ffffff; cursor: pointer;">

                                          <h5 class="text-center">Completar Pedido <i class="fa  fa-chevron-right"></i></h5>

                                        </div>

                                        @else


                                          <div data-type='formaspago'  data-id="{{ $fp->id }}" class=" col-sm-6 col-xs-12 " style="padding:8px;background-color:#f3f3f3;color:#ffffff; cursor: pointer;">

                                            <h5 class="text-center">Saldo Insuficiente <i class="fa  fa-chevron-right"></i></h5>

                                          </div>



                                        @endif



                                       @else

                                       <div data-type='formaspago'  data-id="{{ $fp->id }}" class=" col-sm-6 col-xs-12 procesar btnpg" style="padding:8px;background-color:#3c763d;color:#ffffff; cursor: pointer;">

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


       