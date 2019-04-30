
 <div class="row"> 

            <h3>    Dirección de Envío </h3>

        </div>

 <div class="row direcciones" style="text-align: left;">

                <div class="col-sm-12">

                    @if(isset($direcciones->id))

                        <input type="hidden" name="id_direccion"  id="id_direccion" value="{{ $direcciones->id }}" >

                        <div class="list-group">
                            
                            <div class="col-sm-12" style="font-weight: 600;">

                                <p>{{ $direcciones->state_name.' , '.$direcciones->city_name   }}</p>
                                <p>{{ $direcciones->nombre_estructura.' '.$direcciones->principal_address .' #'. $direcciones->secundaria_address .'-'.$direcciones->edificio_address.', '.$direcciones->detalle_address.', '.$direcciones->barrio_address }}</p>
                                <p>{{ $direcciones->notas }}</p>
                            </div>
                        </div>

                    @else

                        <h3>Debe agregar una dirección de envío  </h3>

                    @endif

                </div>

            <div class="  res_dir"></div>

        </div>