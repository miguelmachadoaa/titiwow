<div class="row">

                <div class="col-sm-3"></div>

                <div class="col-sm-6 cajasombra" style="margin-top: 1em; padding:1em;">

                    <div class="row">

                        <div class="col-sm-12"><h3>Pedido # {{$orden->id}}</h3></div>
                        <div class="col-sm-12"><p><b>Total Compra:</b> {{$orden->monto_total}}</p></div>

                    </div>


                    <div class="row">

                        <div class="col-sm-12">

                            <h4>Formas de Pago </h4>
                            
                        </div>

                    @foreach($orden->pagos as $pago)

                        <div class="col-sm-6"> 

                            <p class="m-0"> <b> 

                                @if($pago->id_forma_pago=='1')

                                Efectivo

                                @elseif($pago->id_forma_pago=='2')

                                Tarjeta Debito

                                @else

                                Tarjeta Credito

                                @endif

                             </b>
                            
                            </p>

                        </div> 

                        <div class="col-sm-6">  

                                <p class="m-0 text-right"> {{number_format($pago->monto_pago,2)}}</p>

                        </div>   

                    @endforeach

                    @foreach($orden->transacciones as $t)

                        @if($t->referencia == 'vuelto' && $t->id_forma_pago=='0')

                            <div class="col-sm-12">
                                
                                <h4>Seleccione la forma de pago para dar vuleto</h4>

                                @foreach( $formaspago as $fp)

                                    @if($fp->vuelto=='1')

                                        @if($fp->moneda=='1')

                                            <button class="btn btn-success w-100 mb-2"> {{$fp->nombre_forma_pago}} Bs. {{$t->monto}}</button>

                                        @else

                                            <button class="btn btn-success w-100 mb-2"> {{$fp->nombre_forma_pago}} Usd. {{$t->monto / $configuracion->tasa_dolar}}</button>

                                        @endif

                                    @endif
                                
                                @endforeach
                                
                            </div>

                        @endif 

                    @endforeach
    
                    </div>

                    <div class="row">
                        
                        <div class="col-sm-12">
                    
                            <button data-id="{{$orden->id}}"  class="btn btn-danger imprimir w-100 my-2 btnImprimir ctrl-p">Imprimir </button>
                            
                            <button data-id="productos" class="btn btn-danger cajita w-100 my-2">Nuevo Pedido</button>
                        
                        </div>

                    </div>

                </div>
                
    </div>