 <br>    

            @if (isset($direcciones->id))


                  <input type="hidden" name="id_direccion"  id="id_direccion" value="{{ $direcciones->id }}" >  


                <div class="col-sm-10 col-sm-offset-1">
                    <div class="panel panel-default">
                        
                        <div class="panel-body">
                            <div class="box-body">
                                <dl class="dl-horizontal">

                                    <dt>Ubicación</dt>
                                    <dd>{{ $direcciones->country_name.', '.$direcciones->state_name.', '.$direcciones->city_name }}</dd>


                                    <dt>Dirección</dt>
                                    <dd>
                                       {{ $direcciones->nombre_estructura.' '.$direcciones->principal_address.' - '.$direcciones->secundaria_address.' '.$direcciones->edificio_address.' '.$direcciones->detalle_address.' '.$direcciones->barrio_address }}
                                    </dd>

                                    <dt>Notas</dt>
                                    <dd>{{ $direcciones->notas }}</dd>
                                    
                                </dl>
                            </div>
                            <!-- /.box-body -->
                        </div>

                    </div>
                </div>

                <div class="col-sm-10 col-sm-offset-1">
                    
                    @if ($editar==1)

                        <button 
                        data-address_id="{{ $direcciones->id }}"
                        data-state_id="{{ $direcciones->state_id }}"
                        data-city_id="{{ $direcciones->city_id }}"
                        data-estructura_id="{{ $direcciones->estructura_id }}"
                        data-principal_address="{{ $direcciones->principal_address }}"
                        data-secundaria_address="{{ $direcciones->secundaria_address }}"
                        data-edificio_address="{{ $direcciones->edificio_address }}"
                        data-detalle_address="{{ $direcciones->detalle_address }}"
                        data-barrio_address="{{ $direcciones->barrio_address }}"
                        data-notas="{{ $direcciones->notas }}"

                         class="btn btn-primary editAddress ">Editar Dirección</button>

                    @else

                        <div class="alert alert-danger">Debe Esperar 24 horas para editar la dirección</div>

                    @endif
                </div>
                

            @else
                <div class="alert alert-danger">
                        <p>El Cliente aun no posee direcciones Registradas</p>
                    </div>
            @endif   
             
      