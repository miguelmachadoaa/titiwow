   
   

        {{ csrf_field() }}

   <div class="col-sm-12">


                            <button class="btn btn-primary seleccionarCliente" type="button" >Seleccionar Cliente</button>

                            <button class="btn btn-primary addCliente" type="button" >Agregar Cliente</button>



                            <h4>Cliente</h4>



                            @if(isset($cart['id_cliente']) && isset($cart['cliente']))

                                <p><b>Nombre:</b>{{$cart['cliente']->first_name.' '.$cart['cliente']->last_name}}</p>
                                <p><b>Documento: </b>{{$cart['cliente']->doc_cliente}}</p>
                                <p><b>Telefono: </b>{{$cart['cliente']->telefono_cliente}}</p>
                                <p><b>Email: </b>{{$cart['cliente']->email}}</p>

                            @endif
                                   
                        </div>


                        <div class="col-sm-12">

                               <h4>Dirección</h4> 

                                @if(isset($cart['direcciones']))

                                <select name="id_address" id="id_address" class="form-control">


                                    @foreach($cart['direcciones'] as $d)

                                        <option value="{{$d->id}}">{{ $d->nombre_estructura.' '.$d->principal_address.' - '.$d->secundaria_address.' '.$d->edificio_address.' '.$d->detalle_address.' '.$d->barrio_address }}</option>

                                    @endforeach
                                    


                                </select>


                            @if(isset($cart['id_cliente']))

                            <br>

                            <button data-id="{{$cart['id_cliente']}}" class="btn btn-primary agregarDireccion" >Agregar Nueva Dirección</button>

                            @endif



                                @endif

                        </div>

                            <div class="col-sm-12">

                                   <h4>Forma de Pago</h4> 

                                   <select class="form-control" name="id_forma_pago" id="id_forma_pago">

                                    @foreach($formaspago as $fp)

                                        <option value="{{$fp->id}}">{{$fp->nombre_forma_pago}}</option>

                                    @endforeach

                                    </select>
                                    
                            </div>


                            <div class="col-sm-12">

                                   <h4>Forma de Envio</h4> 

                                   <select class="form-control" name="id_forma_envio" id="id_forma_envio">

                                    @foreach($formasenvio as $fe)

                                        <option value="{{$fe->id}}">{{$fe->nombre_forma_envios}}</option>

                                    @endforeach

                                    </select>
                                    
                            </div>

