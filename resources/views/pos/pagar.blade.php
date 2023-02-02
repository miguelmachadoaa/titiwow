<div class="row">

            <div  class="row m-0 w-100">

                <div class="col-sm-12 " style="margin-top: 1em;">

                    <div class="row m-0 w-100">
                        
                        <div class="col-sm-4">

                        <h4>Gracias por su compra</h4>
                        @if($cart['resto']==0)

                        <!-- Si el paso es exacto -->

                        @elseif( $cart['resto']<'0' )

                        <!-- Si queda vuelto  -->


                        <h4>Seleccione la forma de pago para dar vuleto</h4>

                            @foreach( $formaspago as $fp)

                                @if($fp->vuelto=='1')

                                    @if($fp->moneda=='1')

                                        <button tabindex="{{$loop->index}}" class="btn btn-success w-100 mb-2 vuelto" data-name="{{$fp->nombre_forma_pago}}"  data-id='{{$fp->id}}' data-monto="{{$cart['resto']}}" data-moneda="1"> {{$fp->nombre_forma_pago}} Bs. {{$cart['resto']}}</button> 

                                    @else

                                        <button tabindex="{{$loop->index}}" class="btn btn-success w-100 mb-2 vuelto" data-name="{{$fp->nombre_forma_pago}}"  data-id='{{$fp->id}}' data-monto="{{$cart['resto']}}" data-moneda="2"> {{$fp->nombre_forma_pago}} Usd. {{number_format($cart['resto'] / $configuracion->tasa_dolar,2)}}</button>

                                    @endif

                                @endif
                            
                            @endforeach

                        @else


                            <h5>Formas de Pago</h5>


                            @foreach($formaspago as $fp)

                                <button tabindex="{{$loop->index}}" data-moneda="{{$fp->moneda}}" data-id="{{$fp->id}}" data-name="{{$fp->nombre_forma_pago}}" class="btn btn-primary w-100 my-2 setpago">{{$fp->nombre_forma_pago}}</button>

                            @endforeach     

                                <button tabindex="{{$loop->index}}" data-id="999" data-moneda='1' data-name="sitef" class="btn btn-primary w-100 my-2 pagarSitef setpago ">Sitef</button> 


                        @endif

                              

                        </div>

                        <div class="col-sm-4">

                            <h5></h5>

                            @if($cart['resto']==0)

                                <div class="row">

                                    <div class="col-sm-12">
                                        <button tabindex="1" class="btn btn-success procesar w-100">Procesar</button>
                                    </div>

                                </div>

                            @elseif( $cart['resto']<'0' )

                                <div class="row">

                                    <div class="col-sm-12">
                                     <h3>Ingrese datos para vuelto </h3>
                                    </div>

                                    <input type="hidden" id="id_forma_pago_vuelto" name="id_forma_pago_vuelto">
                                    <input type="hidden" id="nombre_forma_pago_vuelto" name="nombre_forma_pago_vuelto">
                                    <input type="hidden" id="ticket_vuelto" name="ticket_vuelto">

                                    <div  class="col-sm-12 my-2  vueltofield " >
                                        <input class="form-control" type="number" id="monto_vuelto" name="monto_vuelto" value="{{$cart['resto']}}" placeholder="monto">
                                    </div>

                                    <div style="display: none" class="col-sm-12 my-2 vueltofieldbanco " >
                                        <select name="banco" id="banco" class="form-control">
                                            @foreach($bancos as $b)
                                            <option value="{{$b->codigo_banco}}">{{$b->codigo_banco}} - {{$b->nombre_banco}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                      <div style="display: none" class="col-sm-12 my-2 vueltofieldtelefono " >
                                        <input class="form-control" type="number" id="telefonov" name="telefonov" value="" placeholder="Numero de Teléfono">
                                    </div>

                                    <div style="display: none" class="col-sm-12 my-2 vueltofieldtipodoc " >
                                        <select name="tipodoc" id="tipodoc" class="form-control">
                                            <option value="V">V</option>
                                            <option value="E">E</option>
                                            <option value="J">J</option>
                                            <option value="G">G</option>
                                            <option value="P">P</option>
                                            <option value="C">C</option>
                                        </select>
                                    </div>

                                    <div style="display: none" class="col-sm-12 my-2 vueltofieldcedula " >
                                        <input class="form-control" type="number" id="cedula" name="cedula" value="" placeholder="Numero de Cedula">
                                    </div>

                                    <div class="col-sm-12">

                                        <button tabindex="{{$loop->index}}" class="btn btn-primary addvuelto mb-2 ">Enviar</button>

                                        <button tabindex="{{$loop->index}}" style="display: none"  class="btn btn-primary sendvuelto mb-2 ">Enviar Pago Movil</button>

                                    </div>

                                </div>

                                 <div class="row">
                                    <div class="col-sm-12">
                                        <div id="resvuelto" style="width: 100%;"></div>
                                    </div>
                                </div>

                               
                            @else

                                <div class="row">

                                    <div class="col-sm-12">
                                        <h5 class="datapago">Seleccione Forma de Pago</h5>
                                        <input type="hidden" id="id_forma_pago" name="id_forma_pago" value="0">
                                        <input type="hidden" id="nombre_forma_pago" name="nombre_forma_pago" value="0">

                                        <input type="hidden" id="ticket" name="ticket" value="0">
                                    </div>
                                    
                                    <div class="col-sm-12 my-2 vueltofield pagofield">
                                        <input class="form-control" type="number" id="monto_pago" name="monto_pago" value="{{
                                          //  number_format( floatval($cart['total_bs'])-floatval($cart['pagado_bs']) , 2, ',', '.')
                                          floatval($cart['resto'])
                                            
                                        }}">
                                    </div>

                                    <div class="col-sm-12 my-2 pagofield">
                                        <input class="form-control" type="number" id="referencia" name="referencia" value="" placeholder="Referencia">
                                    </div>

                                     <div style="display: none" class="col-sm-12 my-2 pagofield buscartelefono  vueltofield" >
                                        <input class="form-control" type="number" id="telefono" name="telefono" value="" placeholder="Numero de Teléfono">
                                    </div>

                                    <div style="display: none" class="col-sm-12 my-2 vueltofield " >
                                        <select name="tipodoc" id="tipodoc" class="form-control">
                                            <option value="V">V</option>
                                            <option value="E">E</option>
                                            <option value="J">J</option>
                                            <option value="G">G</option>
                                            <option value="P">P</option>
                                            <option value="C">C</option>
                                        </select>
                                    </div>

                                     <div style="display: none" class="col-sm-12 my-2 vueltofield " >
                                        <input class="form-control" type="number" id="cedula" name="cedula" value="" placeholder="Numero de Cedula">
                                    </div>

                                    <div class="col-sm-12">
                                        <button class="btn btn-primary addpago mb-2 pagofield">Agegar</button>
                                        
                                        <button style="display: none" class="btn btn-primary buscarpago mb-2 buscardield">Buscar pago</button>

                                        <button style="display: none" class="btn btn-primary sendvuelto mb-2 ">Enviar Vuelto</button>

                                    </div>

                                    

                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div id="res" style="width: 100%;"></div>
                                    </div>
                                </div>
                           
                            @endif

                            

                        </div>

                         <div class="col-sm-4 px-4">
                          
                          <h5>Pagos Asignados</h5>

                          @if(isset($cart['pagos']))

                          @foreach($cart['pagos'] as $pago )

                          <div class="row cajasombra">

                            <div class="col-sm-10">

                                    <p class="m-0"><b>Id:</b>{{$pago['id']}}</p>
                                    <p class="m-0"><b>Tipo:</b>{{$pago['name']}}</p>
                                    <p class="m-0"><b>Monto:</b>{{number_format($pago['monto'],2,',','.')}}</p>

                            </div>
                            <div class="col-sm-2 p-0"> 
                                    <button data-id="{{$loop->index}}" class="btn btn-danger delpago"> <i class="fa fa-trash"> </i>   </button>
                                    
                            </div>
                              

                          </div>

                          @endforeach

                          @endif

                        </div>
                       
                    </div>


                     <div class="row">
                                

                        <div class="resSitef" style="width: 100%;">
                            
                        </div>

                    </div> 
                  
                </div>

               
            </div>

<input type="hidden" id="referencia" name="referencia" value="{{$cart['referencia']}}">