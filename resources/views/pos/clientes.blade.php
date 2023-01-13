<div class="row">

                <div class="col-sm-12 mb-2" style="margin-top: 1em;">
                    <div class="row">
                        
                        <div class="col-sm-2">
                            <a  data-id="dashboard" class=" btn btn-primary cajita" href="#"><i class="fa fa-home"></i></a>
                        </div>
                        <div class="col-sm-8">
                            <input class="form-control"  type="text" id="terminocliente" name="terminocliente">
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-primary buscarcliente"><i class="fa fa-search"></i></button>
                           <button data-id="addcliente" class="btn btn-primary cajita"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                  
                </div>

                 <div class="col-sm-12 reserror"> 

         @if(!isset($caja->id))
            <div class="alert alert-danger mt-2"> Debe abrir caja para poder procesar una compra </div>
        @endif

    </div>

                <div class="col-sm-12">
                    

                    @foreach($clientes as $c)

                    <div data-json="{{json_encode($c)}}" class="row cajasombra cliente mt-1 @if($loop->index%2==0) odd @endif">
                        <div class="col-sm-4">
                            <p style="margin: 0">{{$c->first_name.' '.$c->last_name}}</p>
                        </div>
                        <div class="col-sm-3">
                            <p style="margin: 0">{{$c->email}}</p>
                        </div>
                        <div class="col-sm-3">
                            <p style="margin: 0">{{$c->telefono_cliente}}</p>
                        </div>
                        <div class="col-sm-2 ">
                            <button class="btn btn-primary  editcliente"><i class="fa fa-edit"></i></button>
                            <button data-id="{{$c->id}}" class="btn btn-primary    asignacliente"><i class="fa fa-chevron-right"></i></button>
                        </div>
                    </div>
                    @endforeach

                </div>

               
            </div>