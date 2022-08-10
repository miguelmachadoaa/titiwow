<div class="row">


            <div  class="row m-0">

                <div class="col-sm-12 " style="margin-top: 1em;">


                    <div class="row m-0">
                        
                        <div class="col-sm-4">

                            @foreach($formaspago as $fp)

                                <button data-id="{{$fp->id}}" data-name="{{$fp->nombre_forma_pago}}" class="btn btn-primary w-100 my-2 setpago">{{$fp->nombre_forma_pago}}</button>

                            @endforeach                           

                        </div>

                        <div class="col-sm-4">

                            @if($cart['resto']<=0)

                                <div class="row">

                                    <div class="col-sm-12">
                                        <button class="btn btn-success procesar w-100">Procesar</button>
                                    </div>

                                </div>


                            @else


                            <div class="row">

                                <div class="col-sm-12">
                                    <h5 class="datapago">Seleccione Forma de Pago</h5>
                                    <input type="hidden" id="id_forma_pago" name="id_forma_pago" value="0">
                                    <input type="hidden" id="nombre_forma_pago" name="nombre_forma_pago" value="0">
                                </div>
                                
                                <div class="col-sm-12 my-2">
                                    <input class="form-control" type="number" id="monto_pago" name="monto_pago" value="{{$cart['total']-$cart['pagado']}}">
                                </div>

                                <div class="col-sm-12">
                                    <button class="btn btn-primary addpago">Agegar</button>
                                </div>

                                

                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div id="res"></div>
                                </div>
                            </div>
                           



                            @endif

                            

                        </div>

                         <div class="col-sm-4 px-4">
                          
                          <h5>Pagos Asignados</h5>

                          @if(isset($cart['pagos']))

                          @foreach($cart['pagos'] as $pago )

                          <div class="row">

                            <div class="col-sm-10">

                                    <p> {{$pago['id']}} / {{$pago['name']}}/ {{$pago['monto']}} </p>

                            </div>
                            <div class="col-sm-2"> 
                                    <button data-id="{{$loop->index}}" class="btn btn-danger delpago"> <i class="fa fa-trash"> </i>   </button>
                                    
                            </div>
                              

                          </div>

                            

                          @endforeach

                          @endif

                        </div>
                       
                    </div>
                  
                </div>

                

               
            </div>