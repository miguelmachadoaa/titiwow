@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar Configuracion
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Editar Configuracion
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Configuracions</li>
        <li class="active">Editar</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Editar Configuracion
                    </h4>
                </div>
                <div class="panel-body">

         <input type="hidden" name="base" id="base" value="{{ url('/') }}">
                    
                    
                        {!! Form::model($configuracion, ['url' => URL::to('admin/configuracion/'. $configuracion->id), 'method' => 'put', 'class' => 'form-horizontal']) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}
                          
                            <div class="form-group {{ $errors->first('nombre_tienda', 'has-error') }}">
                                <label for="title" class="col-sm-2 control-label">
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
                            <hr />
                            <div class="form-group {{ $errors->first('limite_amigos', 'has-error') }}">
                                <label for="title" class="col-sm-2 control-label">
                                    Limite de Amigos Alpina
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="limite_amigos" name="limite_amigos" class="form-control" placeholder="Limite de Amigos Alpina"
                                        value="{!! old('limite_amigos', $configuracion->limite_amigos) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('limite_amigos', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>
                            <hr />
                            <div class="form-group {{ $errors->first('id_mercadopago', 'has-error') }}">
                                <label for="title" class="col-sm-2 control-label">
                                    ID Mercadopago
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="id_mercadopago" name="id_mercadopago" class="form-control" placeholder="ID Mercadopago"
                                        value="{!! old('id_mercadopago', $configuracion->id_mercadopago) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('id_mercadopago', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('key_mercadopago', 'has-error') }}">
                                <label for="title" class="col-sm-2 control-label">
                                    Key Mercadopago
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="key_mercadopago" name="key_mercadopago" class="form-control" placeholder="Key Mercadopago"
                                        value="{!! old('key_mercadopago', $configuracion->key_mercadopago) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('key_mercadopago', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>

                            <fieldset>  

                                <legend>Ciudades Permitidas para compras</legend>

                                <div class="row">   

                                         <!-- Select State -->

                                    <div class="form-group col-sm-3 col-xs-12" style="margin: 0 0 15px 0;">
                                        
                                        <label for="select21" class=" control-label">Departamento</label>
                                            
                                            <div class="" >

                                                    <select id="state_id" name="state_id" class="form-control select2">

                                                        <option value="">Seleccione</option>
                                                        
                                                        @foreach($states as $state)

                                                        <option value="{{ $state->id }}">
                                                                {{ $state->state_name}}</option>
                                                        @endforeach
                                                        
                                                      
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group col-sm-3 col-xs-12" style="margin: 0 0 15px 0;">
                                                
                                                <label for="select21" class=" control-label">Ciudad</label>
                                                <div class="" >

                                                    <select id="city_id" name="city_id" class="form-control select2">
                                                        <option value="">Seleccione</option>
                                                      
                                                    </select>

                                                </div>

                                            </div>

                                            <div class="form-group col-sm-3 col-xs-12" style="margin: 0 0 15px 0;">
                                                <br>

                                                <button type="button" class="btn btn-default" onclick="addCiudad();"> Agregar </button>
                                            </div>

                                    </div>  

                                    <div class="row">
                                        
                                        <div class="ciudades">
                                            
                                                @if(count($cities))

                                                    <table class="table table-responsive">
                                                        <thead>
                                                            <tr>
                                                                <td>Ciudad</td>
                                                                <td>Accion</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                    @foreach($cities as $ciudad)
                                                       <tr>
                                                           <td>{{ $ciudad->state_name.' - '.$ciudad->city_name }}</td>
                                                           <td>
                                                               <button data-id="{{ $ciudad->id }}" type="button" class="btn btn-danger btn-xs delCiudad"><i class="fa fa-trash"></i></button>
                                                           </td>
                                                       </tr>        

                                                    @endforeach  

                                                  </tbody>
                                                    </table>

                                                @endif


                                                <hr>





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
