<div class="row">

                <div class="col-sm-12 " style="margin-top: 1em;">
                    <div class="row">
                        
                        <div class="col-sm-2">
                            <a  data-id="dashboard" class=" btn btn-primary cajita" href="#"><i class="fa fa-home"></i></a>
                        </div>
                        <div class="col-sm-8">
                           <p>Pedidos</p>
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-primary"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                  
                </div>

                <div class="col-sm-12">
                    
                    @foreach($ordenes as $o)

                        <div class="row cajasombra mt-2" >
                            
                            <div class="col-sm-1">{{$o->id}}</div>
                            <div class="col-sm-4">{{$o->cliente->first_name.' '.$o->cliente->last_name}}</div>
                            <div class="col-sm-2">{{$o->monto_total}} / {{$o->estado->estatus_nombre}}</div>
                             <div class="col-sm-4">{{$o->cajero->first_name.' '.$o->cajero->last_name}}</div>
                            <div class="col-sm-1">
                                <button type="button" data-id="{{$o->id}}" class="btn btn-primary detalleorden"><i class="fa fa-eye"></i></button>
                            </div>

                        </div>

                    @endforeach


                </div>

               
            </div>