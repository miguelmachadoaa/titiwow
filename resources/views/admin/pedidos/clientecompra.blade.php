   
   

        {{ csrf_field() }}

   <div class="col-sm-12">

                            <p class="pcaja"><b>Paso 1. Seleccionar Cliente</b>  

                            <span >
                                <button class="btn btn-primary seleccionarCliente" type="button" ><i class="fa fa-search"></i></button>

                                <button class="btn btn-primary addCliente" type="button" > <i class="fa fa-plus"></i> </button>
                            </span>
                            </p>


                            @if(isset($cart['id_cliente']) && isset($cart['cliente']))

                            <div class="caja" style=""  >
                                {{$cart['cliente']->first_name.' '.$cart['cliente']->last_name}}

                                <span >
                                <button class="btn btn-link mostrarCliente" type="button" ><i class="fa fa-eye"></i></button>

                                
                            </span>
                            </div>

                            

                            <div class="descripcion_cliente " style="display:none">
                                <p><b>Nombre: </b> {{$cart['cliente']->first_name.' '.$cart['cliente']->last_name}}</p>
                                <p><b>Documento: </b> {{$cart['cliente']->doc_cliente}}</p>
                                <p><b>Telefono: </b> {{$cart['cliente']->telefono_cliente}}</p>
                                <p><b>Email: </b> {{$cart['cliente']->email}}</p>

                                @if($cart['cliente']->origen=='1')

                                    <p class=""> <b>Origen:</b> Tomapedidos  </p>

                                @else

                                    <p class=""><b>Origen:</b>  Web  </p>

                                @endif

                            </div>

                                

                            @endif
                                   
                        </div>


                        <div class="col-sm-12">

                            <p class="pcaja"><b>Paso 2. Seleccionar Dirección</b>


                            <span >
                                @if(isset($cart['id_cliente']))

                                    <button type="button" data-id="{{$cart['id_cliente']}}" class="btn btn-primary agregarDireccion" ><i class="fa fa-plus"></i></button>

                                @else
                                    <button disable type="button"  class="btn btn-primary " ><i class="fa fa-plus"></i></button>
                                @endif
                            </span>
                            </p>

                                @if(isset($cart['id_cliente']))
                                @if(isset($cart['direcciones']))

                                <div class="caja" style="">
                                        <select name="id_address" id="id_address" class="form-control">

                                                @foreach($cart['direcciones'] as $d)

                                                    <option @if($cart['id_direccion']==$d->id) {{'Selected'}} @endif value="{{$d->id}}">{{ $d->state_name.' '.$d->city_name.' '.$d->nombre_estructura.' '.$d->principal_address.' - '.$d->secundaria_address.' '.$d->edificio_address.' '.$d->detalle_address.' '.$d->barrio_address }}</option>

                                                @endforeach

                                        </select>
                                </div>


                                @endif
                                @endif

                        </div>

                        <input type="hidden" name="notas_orden" id="notas_orden">   

                        <!--div class="col-sm-12">
                            <h3>Observaciónes de la compra </h3>

                            <textarea class="form-control" name="notas_orden" id="notas_orden" cols="30" rows="5">
                                
                            </textarea>

                        </div-->


                            <div class="col-sm-6">

                                <p class="pcaja" > <b>Paso 3. Forma de Pago</b></p>

                                <div class="caja">
                                    <select class="form-control" name="id_forma_pago" id="id_forma_pago">

                                        @foreach($formaspago as $fp)

                                            <option value="{{$fp->id}}">{{$fp->nombre_forma_pago}}</option>

                                        @endforeach

                                    </select>
                                </div>
                                    
                            </div>


                            <div class="col-sm-6">

                                <p class="pcaja"><b>Paso 4. Forma de Envio</b></p>

                                <div class="caja">

                                <select class="form-control" name="id_forma_envio" id="id_forma_envio">

                                    @foreach($formasenvio as $fe)

                                        <option value="{{$fe->id}}">{{$fe->nombre_forma_envios}}</option>

                                    @endforeach

                                    </select>

                                </div>
                                    
                            </div>

