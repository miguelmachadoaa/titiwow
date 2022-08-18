<div class="row">

                <div class="col-sm-12 " style="margin-top: 1em;">
                    <div class="row">
                        
                        <div class="col-sm-2">
                            <a  data-id="dashboard" class=" btn btn-primary cajita" href="#"><i class="fa fa-home"></i></a>
                        </div>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" name="termino" id="termino" value="" placeholder="Buscar">
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-primary"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                  
                </div>

                <div class="col-sm-12">

                        <div class="row cajasombra mt-2" >
                            
                            <div class="col-sm-6"><b>Forma de Pago</b> </div>
                            <div class="col-sm-6" style="text-align: right;"><b>Total</b></div>
                            

                        </div>
                    
                    @foreach($pagos as $pago)

                        <div class="row cajasombra mt-2" >
                          
                           
                            <div class="col-sm-6" >@if(isset($pago->formapago->nombre_forma_pago)){{$pago->formapago->nombre_forma_pago}}@endif</div>

                             <div class="col-sm-6" style="text-align: right;">{{$pago->total_pagos}}</div>

                        </div>

                    @endforeach


                </div>

               
            </div>