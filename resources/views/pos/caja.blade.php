<div class="row">

                <div class="col-sm-12 " style="margin-top: 1em;">
                    <div class="row">
                        
                        <div class="col-sm-2">
                            <a  data-id="dashboard" class=" btn btn-primary cajita" href="#"><i class="fa fa-home"></i></a>
                        </div>
                        <div class="col-sm-8">
                           <input class="form-control" type="text" name="terminocaja" id="terminocaja" value="" placeholder="Buscar" >
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-primary buscarcaja"><i class="fa fa-search"></i></button>
                            <button data-id="addcaja" type="button" class="btn btn-primary cajita"><i class="fa fa-plus"></i></button>
                            
                        </div>
                    </div>
                  
                </div>

                <div class="col-sm-12">
                    
                    @foreach($cajas as $c)

                        <div class="row cajasombra mt-2 cajas" data-json="{{json_encode($c)}}" >
                            
                            <div class="col-sm-2">{{$c->monto_inicial}}</div>
                            <div class="col-sm-2">{{$c->monto_final}}</div>
                            <div class="col-sm-2">{{$c->fecha_inicio}}</div>
                            <div class="col-sm-2">{{$c->fecha_cierre}}</div>
                            <div class="col-sm-2">
                                @if($c->estado_registro=='1')
                                 <div class="badge badge-success" >{{'Abierta'}}</div>
                                @else
                                 <div class="badge badge-danger" >{{'Cerrada'}}</div>
                                @endif
                             </div>
                            
                            <div class="col-sm-2">
                                <button type="button" data-id="{{$c->id}}" class="btn btn-primary detallecaja"><i class="fa fa-eye"></i></button>

                                @if($c->estado_registro=='1')
                                 <button type="button" data-id="{{$c->id}}" class="btn btn-danger cerrarcaja"><i class="fa fa-power-off"></i></button>

                                @endif
                            </div>

                        </div>

                    @endforeach


                </div>

               
            </div>