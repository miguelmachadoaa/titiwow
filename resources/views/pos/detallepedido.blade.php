<div class="row">

                <div class="col-sm-12 " style="margin-top: 1em;">
                    <div class="row">
                        
                        <div class="col-sm-2">
                            <a  data-id="pedidos" class=" btn btn-primary cajita" href="#"><i class="fa fa-undo"></i></a>
                        </div>
                        <div class="col-sm-6">
                            <h4>Detalle de Pedido</h4>
                        </div>
                        <div class="col-sm-4" style="text-align: right;">
                            <button class="btn btn-primary"><i class="fa fa-list"></i></button>
                            <button class="btn btn-primary"><i class="fa fa-print"></i></button>
                        </div>
                    </div>
                  
                </div>

                <div class="col-sm-12 mt-2">
                    
                    <div class="row">

                        <div class="col-sm-6 " style="padding: 0.5em;">

                            <div class="cajasombra">

                            <h3><input type="hidden" id="referencia" name="referencia" value="{{$orden->referencia}}">Referencia: {{$orden->referencia}}</h3>

                        <div class="col-sm-12"><h5>Vendedor</h5></div>

                                
                            <div class="row">
                                <div class="col-sm-6">Id:</div>
                                <div class="col-sm-6">{{$orden->id}}</div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">Cajero:</div>
                                <div class="col-sm-6">{{$orden->cajero->first_name.' '.$orden->cajero->last_name}}</div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">Fecha:</div>
                                <div class="col-sm-6">{{$orden->created_at}}</div>
                            </div>
                            </div>

                        </div>

                        <div class="col-sm-6 " style="padding: 0.5em;">

                            <div class="cajasombra">

                        <div class="col-sm-12"><h5>Cliente</h5></div>


                             <div class="row">
                                <div class="col-sm-6">Id:</div>
                                <div class="col-sm-6">{{$orden->cliente->id}}</div>
                            </div>


                            <div class="row">
                                <div class="col-sm-6">Cliente:</div>
                                <div class="col-sm-6">{{$orden->cliente->first_name.' '.$orden->cliente->last_name}}</div>
                            </div>


                            <div class="row">
                                <div class="col-sm-6">Email:</div>
                                <div class="col-sm-6">{{$orden->cliente->email}}</div>
                            </div>
                            </div>

                            
                        </div>
                    </div>
                </div>



                <div class="col-sm-12  mt-2" style="padding: 0.5em;">

                    <div class="cajasombra">

                        <div class="row " >
                            
                            <div class="col-sm-3">
                                <p>Nombre</p>
                            </div>
                            <div class="col-sm-1">Cant.</div>
                            <div class="col-sm-2">Precio </div>
                            <div class="col-sm-2">Descuento </div>
                            <div class="col-sm-2">Impuesto</div>
                            <div class="col-sm-2">Total </div>

                        </div>

                    
                    @foreach($orden->detalles as $d)



                        <div class="row " >
                            
                            <div class="col-sm-3">
                                <p style="margin: 0;">{{$d->producto->nombre_producto}}</p>
                                <p>{{$d->producto->referencia_producto}}</p>
                            </div>
                            <div class="col-sm-1">{{$d->cantidad}}</div>
                            <div class="col-sm-2">{{number_format($d->precio_unitario,2)}} </div>
                            <div class="col-sm-2">{{number_format($d->monto_descuento,2)}} </div>
                            <div class="col-sm-2">{{number_format($d->monto_impuesto,2)}} </div>
                            <div class="col-sm-2">{{number_format($d->precio_unitario*$d->cantidad,2)}} </div>

                        </div>

                    @endforeach

                    </div>


                </div>




                 <div class="col-sm-12 mt-2">

                     <div class="row mt-2">
                        <div class="col-sm-12">
                            <div class="resSitef"></div>
                        </div>
                    </div>
                    
                    <div class="row">
                        
                        <div class="col-sm-6 " style="padding: 0.5em;">

                            <div class="cajasombra">
                                
                            

                            @foreach($orden->pagos as $pago)

                            <div class="row" >
                                <div class="col-sm-6">{{$pago->formapago->nombre_forma_pago}}:</div>
                                <div class="col-sm-6">{{$pago->monto_pago}}</div>
                            </div>

                            @endforeach

                            </div>

                            

                            
                        </div>

                        <div class="col-sm-6 " style="padding: 0.5em;">

                            <div class="cajasombra">

                             <div class="row">
                                <div class="col-sm-6">Subtotal:</div>
                                <div class="col-sm-6">{{$orden->monto_total - $orden->monto_impuesto}}</div>
                            </div>


                            <div class="row">
                                <div class="col-sm-6">Descuento:</div>
                                <div class="col-sm-6">{{$orden->monto_descuento}}</div>
                            </div>


                            <div class="row">
                                <div class="col-sm-6">Iva:</div>
                                <div class="col-sm-6">{{$orden->monto_impuesto}}</div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">Total:</div>
                                <div class="col-sm-6">{{$orden->monto_total}}</div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6"><button class="btn btn-danger cancelarOrden">Cancelar Orden</button></div>
                                <div class="col-sm-6"><button data-id="{{$orden->id}}" class="btn btn-success btnImprimir">Reimprimir</button></div>
                            </div>

                            </div>

                            
                        </div>

                    </div>

                   
                </div>



               
            </div>