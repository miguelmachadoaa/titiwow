<div class="row" id="addAddressForm" style="margin-top: 1em; 

@if(count($errors))



@else

    display: none;

@endif


">


                            
                            <div class="col-sm-12" style="    border: 1px solid rgba(0,0,0,0.1);    padding: 2em;    margin: 0em -2em;">
                                

                        <h3 style="text-align: center;margin-bottom: 1em;">Agregar Direccion</h3>
                        
                        <form method="POST" action="{{secure_url('cart/storedir')}}" id="addDireccionForm" name="addDireccionForm" class="form-horizontal">


                            {{ csrf_field() }}


                             <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('titulo', 'has-error') }}">
                                <div class="" >
                                     <input required="true" type="text" id="titulo" name="titulo" placeholder="Titulo" class="form-control" value="{!! old('titulo') !!}" >
                                </div>
                                {!! $errors->first('titulo', '<span class="help-block">:message</span>') !!}
                            </div>

                            

                                <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('cod_alpinista', 'has-error') }}">
                                <div class="" >
                                    <select id="state_id" name="state_id" class="form-control">
                                        <option value="">Seleccione Departamento</option>     
                                        @foreach($states as $state)
                                        <option value="{{ $state->id }}">
                                                {{ $state->state_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {!! $errors->first('state_id', '<span class="help-block">:message</span>') !!}
                            </div>
                            <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('city_id', 'has-error') }}">
                                <div class="" >
                                    <select id="city_id" name="city_id" class="form-control">
                                        <option value="">Seleccione Ciudad</option>
                                        
                                    </select>
                                </div>
                                {!! $errors->first('city_id', '<span class="help-block">:message</span>') !!}
                            </div>
                            <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('calle_address', 'has-error') }}">

                                <div class="input-group " style="{{ $errors->first('id_estructura_address', 'has-error') }}">
                                    <div class="" >
                                    <select required="true" id="id_estructura_address" name="id_estructura_address" class="form-control">
                                        @foreach($estructura as $estru)
                                        <option value="{{ $estru->id }}">
                                        {{ $estru->nombre_estructura}} </option>
                                        @endforeach
                                    </select>
                                </div>
                                    <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>

                                    <input type="text" id="principal_address" name="principal_address" class="form-control" required="true" value="{!! old('principal_address') !!}" >
                                    <span class="input-group-addon">#</span>
                                    <input type="text" id="secundaria_address" name="secundaria_address" class="form-control" required="true" value="{!! old('secundaria_address') !!}" >
                                    <span class="input-group-addon">-</span>
                                    <input type="text" id="edificio_address" name="edificio_address" class="form-control" required="true" value="{!! old('edificio_address') !!}" >

                                    <!-- insert this line -->
                                    <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                                </div>
                                {!! $errors->first('detalle_address', '<span class="help-block">:message</span>') !!}
                                {!! $errors->first('id_estructura_address', '<span class="help-block">:message</span>') !!}

                                {!! $errors->first('principal_address', '<span class="help-block">:message</span>') !!}

                                {!! $errors->first('secundaria_address', '<span class="help-block">:message</span>') !!}

                                {!! $errors->first('edificio_address', '<span class="help-block">:message</span>') !!}

                            </div>
                            <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('detalle_address', 'has-error') }}">
                                <input type="text" class="form-control" id="detalle_address" name="detalle_address" required="true" placeholder="Apto, Puerta, Interior"
                                       value="{!! old('detalle_address') !!}" >
                                {!! $errors->first('detalle_address', '<span class="help-block">:message</span>') !!}
                            </div>
                            <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('barrio_address', 'has-error') }}">
                                <input type="text" class="form-control" id="barrio_address" name="barrio_address" required="true" placeholder="Barrio"
                                       value="{!! old('barrio_address') !!}" >
                                {!! $errors->first('barrio_address', '<span class="help-block">:message</span>') !!}
                            </div>

                            <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('notas', 'has-error') }}">
                                <textarea style="margin: 4px 0;" id="notas" name="notas" type="text" placeholder="Notas" class="form-control" ></textarea>

                                {!! $errors->first('notas', '<span class="help-block">:message</span>') !!}
                            </div>

                           
                            <div class="clearfix"></div>

                            <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('notas', 'has-error') }}">

                                <button class="btn btn-primary" type="submit" >Crear </button>

                                 <button type="button"  class="btn btn-danger showAddAddress">Cerrar</button>


                               

                               
                            </div>

                        </form>

                         </div>
                        </div>