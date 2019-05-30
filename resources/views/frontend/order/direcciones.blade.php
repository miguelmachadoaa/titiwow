<div class="col-sm-12">

  

@if(count($direcciones))

    <div class="form-group col-sm-10 col-sm-offset-1">

    @foreach($direcciones as $direccion)

        <!-- Se construyen las opciones de envios -->

       <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <input type="radio" name="id_direccion" class="custom-radio" id="id_direccion" value="{{ $direccion->id }}" @if($direccion->default_address) checked  @endif>  {{ $direccion->nickname_address }}
                            </h3>
                            
                        </div>
                        <div class="panel-body">
                            <div class="box-body">
                                <dl class="dl-horizontal">

                                    <dt>Pais</dt>
                                    <dd>{{ $direccion->country_name }}</dd>

                                    <dt>Departamento</dt>
                                    <dd>{{ $direccion->state_name }}</dd>

                                    <dt>Ciudad</dt>
                                    <dd>{{ $direccion->city_name }}</dd>

                                    <dt>Direccion</dt>
                                    <dd>
                                       {{ $direccion->calle_address.' '.$direccion->calle2_address }}
                                    </dd>

                                    <dt>Codigo Postal</dt>
                                    <dd>{{ $direccion->codigo_postal_address }}</dd>

                                    <dt>Telefono</dt>
                                    <dd>{{ $direccion->telefono_address }}</dd>

                                    <dt>Notas</dt>
                                    <dd>{{ $direccion->notas }}</dd>

                                    
                                </dl>
                            </div>
                            <!-- /.box-body -->
                        </div>
                    </div>
                </div>
   
        

    @endforeach  

    </div>

@endif

</div>




<hr>
