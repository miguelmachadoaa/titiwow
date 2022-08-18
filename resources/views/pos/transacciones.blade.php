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
                            
                            <div class="col-sm-2">Fecha</div>
                            <div class="col-sm-2">Ingreso</div>
                            <div class="col-sm-2">Salida</div>
                             <div class="col-sm-3">Metodo</div>
                             <div class="col-sm-3">Referencia</div>
                            

                        </div>
                    
                    @foreach($pagos as $pago)

                        <div class="row cajasombra mt-2" >
                           <div class="col-sm-2">{{$pago->created_at}}</div>
                            <div class="col-sm-2">{{$pago->monto_pago}}</div>
                            <div class="col-sm-2">0</div>
                             <div class="col-sm-3">@if(isset($pago->formapago->nombre_forma_pago)){{$pago->formapago->nombre_forma_pago}}@endif</div>
                             <div class="col-sm-3">#{{$pago->id_orden}}</div>

                        </div>

                    @endforeach


                </div>

               
            </div>