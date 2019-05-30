
 <div class="row"> 

            <h3>    Dirección de Envío 

            @if($configuracion->editar_direccion=='0')

                <button style="float: right;" class="btn btn-primary showAddAddress">Agregar Dirección</button>

            @endif</h3>

        </div>



    

    @include('frontend.includes.addaddress')



 <div class="row direcciones" style="text-align: left;">

                <div class="col-sm-12">

                    @if(count($direcciones)>0)


                        <div class="list-group">
                            
                            <div class="col-sm-12" style="font-weight: 600;">

                                <select name="id_direccion" id="id_direccion" class="form-control">
                                    
                                    @foreach($direcciones as $dir)

                                    <option value="{{$dir->id}}" @if($dir->default_address) {{'selected'}} @endif>

                                        <p>{{ $dir->titulo   }} -</p>
                                <p>{{ $dir->state_name.' , '.$dir->city_name   }}</p>
                                <p>{{ $dir->nombre_estructura.' '.$dir->principal_address .' #'. $dir->secundaria_address .'-'.$dir->edificio_address.', '.$dir->detalle_address.', '.$dir->barrio_address }}</p>
                                <p>{{ $dir->notas }}</p>
                                        
                                    </option>

                                    @endforeach
                                </select>

                                <p>Si quieres editar una dirección <a class="btn btn-link" target="_blank" style="color: #d5006e !important;" href="{{secure_url('misdirecciones')}}">Clic aqui </a></p>

                                
                            </div>
                        </div>

                    @else

                        <h3>Debe agregar una dirección de envío  </h3>

                    @endif

                </div>

            <div class="  res_dir"></div>

        </div>