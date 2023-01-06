@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar Configuración General
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Editar Configuración General
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Configuración</li>
        <li class="active">Editar</li>
    </ol>
</section>

<!-- Main content -->
<section class="content contain_body">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Editar Configuración
                    </h4>
                </div>
                <div class="panel-body">

         <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">
                    
                    
                        {!! Form::model($configuracion, ['url' => secure_url('admin/configuracion/'. $configuracion->id), 'method' => 'put', 'class' => 'form-horizontal']) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}
                          
                            <div class="form-group {{ $errors->first('nombre_tienda', 'has-error') }}">
                                <label for="nombre_tienda" class="col-sm-2 control-label">
                                    Nombre Tienda
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="nombre_tienda" name="nombre_tienda" class="form-control" placeholder="Nombre Tienda"
                                        value="{!! old('nombre_tienda', $configuracion->nombre_tienda) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('nombre_tienda', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>

                             <div class="form-group {{ $errors->first('direccion_tienda', 'has-error') }}">
                                <label for="direccion_tienda" class="col-sm-2 control-label">
                                    Nombre Tienda
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="direccion_tienda" name="direccion_tienda" class="form-control" placeholder="Dirección Tienda"
                                        value="{!! old('direccion_tienda', $configuracion->direccion_tienda) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('nombre_tienda', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>

                             <div class="form-group {{ $errors->first('rif_tienda', 'has-error') }}">
                                <label for="rif_tienda" class="col-sm-2 control-label">
                                    Nombre Tienda
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="rif_tienda" name="rif_tienda" class="form-control" placeholder="Rif Tienda"
                                        value="{!! old('rif_tienda', $configuracion->rif_tienda) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('nombre_tienda', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->first('base_url', 'has-error') }}">
                                <label for="base_url" class="col-sm-2 control-label">
                                    Base Url
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="base_url" name="base_url" class="form-control" placeholder="Base Url"
                                        value="{!! old('base_url', $configuracion->base_url) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('base_url', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>
                            <hr />
                           


                            <div class="form-group  {{ $errors->first('mostrar_agotados', 'has-error') }}">
                                <label for="select21" class="col-sm-2 control-label">
                                    Mostrar Agotados
                                </label>
                                <div class="col-sm-5">   
                                 <select id="mostrar_agotados" name="mostrar_agotados" class="form-control ">
                                    <option value="">Seleccione</option>
                                       
                                        <option value="{{ 0 }}"
                                                @if($configuracion->mostrar_agotados == 0) selected="selected" @endif >No Mostrar</option>

                                        <option value="{{ 1 }}"
                                                @if($configuracion->mostrar_agotados == 1) selected="selected" @endif >Mostrar</option>
                                       
                                </select>
                                <div class="col-sm-4">
                                    {!! $errors->first('mostrar_agotados', '<span class="help-block">:message</span> ') !!}
                                </div>
                                  
                                </div>
                               
                            </div>



                             <div class="form-group  {{ $errors->first('user_activacion', 'has-error') }}">
                                <label for="select21" class="col-sm-2 control-label">
                                    Activación de Usuario
                                </label>
                                <div class="col-sm-5">   
                                 <select id="user_activacion" name="user_activacion" class="form-control ">
                                    <option value="">Seleccione</option>
                                        
                                       
                                        <option value="{{ 0 }}"
                                                @if($configuracion->user_activacion == 0) selected="selected" @endif >Automatico</option>

                                        <option value="{{ 1}}"
                                                @if($configuracion->user_activacion == 1) selected="selected" @endif >Manual</option>
                                       
                                </select>
                                <div class="col-sm-4">
                                    {!! $errors->first('user_activacion', '<span class="help-block">:message</span> ') !!}
                                </div>
                                  
                                </div>
                               
                            </div>


                           


                            

                            <fieldset>

                                <br />
                                <h4>Tasa Dolar</h4>
                                <hr>

                                <div class="form-group {{ $errors->first('tasa_dolar', 'has-error') }}">
                                    <label for="tasa_dolar" class="col-sm-2 control-label">
                                        Tasa Dolar
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="number" min="0" step="0.01" id="tasa_dolar" name="tasa_dolar" class="form-control" placeholder="tasa dolar "
                                            value="{!! old('tasa_dolar', $configuracion->tasa_dolar) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('tasa_dolar', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>


                                </fieldset>



                                <fieldset>

                                    <br />
                                    <h4>Impresora</h4>
                                    <hr>

                                    <div class="form-group {{ $errors->first('nombre_impresora', 'has-error') }}">
                                        <label for="nombre_impresora" class="col-sm-2 control-label">
                                            Nombre de Impresora
                                        </label>
                                        <div class="col-sm-5">
                                            <input type="text" id="nombre_impresora" name="nombre_impresora" class="form-control" placeholder="Nombre Impresora "
                                                value="{!! old('nombre_impresora', $configuracion->nombre_impresora) !!}">
                                        </div>
                                        <div class="col-sm-4">
                                            {!! $errors->first('nombre_impresora', '<span class="help-block">:message</span> ') !!}
                                        </div>
                                    </div>


                                    <div class="form-group {{ $errors->first('columnas_impresora', 'has-error') }}">
                                        <label for="columnas_impresora" class="col-sm-2 control-label">
                                            Columnas de Impresión
                                        </label>
                                        <div class="col-sm-5">
                                            <input type="number" step="1" min="1" id="columnas_impresora" name="columnas_impresora" class="form-control" placeholder="Columnas Impresora "
                                                value="{!! old('columnas_impresora', $configuracion->columnas_impresora) !!}">
                                        </div>
                                        <div class="col-sm-4">
                                            {!! $errors->first('columnas_impresora', '<span class="help-block">:message</span> ') !!}
                                        </div>
                                    </div>



                                    </fieldset>




                             
                      
                       <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                
                                <a class="btn btn-danger" href="{{ route('admin.configuracion.index') }}">
                                    Cancelar
                                </a>

                                <button type="submit" class="btn btn-success">
                                    Actualizar
                                </button>
                            </div>
                        </div>
                    </form>
                   
                </div>
            </div>
        </div>
    </div>
    <!-- row-->
</section>

@stop


@section('footer_scripts')
<script src="https://cdn.tiny.cloud/1/qc49iemrwi4gmrqtiuvymiviycjklawxnqmtcnvorw0hckoj/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>


<script >

    function addCiudad(){

        city_id = $('#city_id').val();
        var base = $('#base').val();

         $.ajax({
            type: "POST",
            data:{ city_id},
            url: base+"/admin/configuracion/storecity",
                
            complete: function(datos){     

                $(".ciudades").html(datos.responseText);

            }
        });



    }




 $(document).ready(function(){
        //Inicio select región

        tinymce.init({
            selector:'#popup_mensaje',
            width: '100%',
            height: 300
        });

            $(document).on('click', '.delCiudad', function(){
                id=$(this).data('id');
        var base = $('#base').val();
                

                $.ajax({
                type: "POST",
                data:{ id},
                url: base+"/admin/configuracion/delcity",
                    
                complete: function(datos){     

                    $(".ciudades").html(datos.responseText);

                }
            });


            });

            //inicio select ciudad
            $('select[name="state_id"]').on('change', function() {
                    var stateID = $(this).val();
                var base = $('#base').val();

                    if(stateID) {
                        $.ajax({
                            url: base+'/configuracion/cities/'+stateID,
                            type: "GET",
                            dataType: "json",
                            success:function(data) {

                                
                                $('select[name="city_id"]').empty();
                                $.each(data, function(key, value) {
                                    $('select[name="city_id"]').append('<option value="'+ key+'_'+value +'">'+ value +'</option>');
                                });

                            }
                        });
                    }else{
                        $('select[name="city_id"]').empty();
                    }
                });
            //fin select ciudad
        });

 </script>

@stop
