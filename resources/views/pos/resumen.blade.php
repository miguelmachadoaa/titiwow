<div class="row">

                <div class="col-sm-3"></div>


                <div class="col-sm-6 cajasombra" style="margin-top: 1em;">

                    <div class="row">

                        <div class="col-sm-12"><h3>Pedido # {{$orden->id}}</h3></div>
                        <div class="col-sm-12"><p><b>Total Compra:</b> {{$orden->monto_total}}</p></div>

                    </div>


                    <div class="row">

                        <div class="col-sm-12">

                            <h3>Formas de Pago </h3>
                            
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
                                

                            : </b></p>

                        </div> 

                        <div class="col-sm-6">  

                                <p class="m-0 text-right"> {{number_format($pago->monto_pago,2)}}</p>

                        </div>   

                    @endforeach

                    </div>

                    <div class="row">
                        
                        <div class="col-sm-12">
                    
                    <button class="btn btn-danger imprimir w-100 my-2">Imprimir</button>
                    <button data-id="productos" class="btn btn-danger cajita w-100 my-2">Nuevo Pedido</button>
                </div>


                    </div>


                </div>

               

                
                
    </div>