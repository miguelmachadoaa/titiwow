@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
    Direcciones
    @parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Direcciones
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Direcciones</li>
        <li class="active">
            Crear
        </li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="users-add" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Editar Dirección
                    </h4>
                </div>
                <div class="panel-body">
                    {!! $errors->first('slug', '<span class="help-block">Another role with same slug exists, please choose another name</span> ') !!}
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                    <form method="POST" action="{{secure_url('admin/clientes/upddir')}}" id="addDireccionForm" name="addDireccionForm" class="form-horizontal     ">

                     {{ csrf_field() }}

                     <input type="hidden" name="id_address" id="id_address" value="{{$direccion->id}}">


                     <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('titulo', 'has-error') }}">


                        <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Nombre para esta dirección" value="{!! old('titulo', $direccion->titulo) !!}" >

                        {!! $errors->first('titulo', '<span class="help-block">:message</span>') !!}
                    
                    </div>
                            

                    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('cod_alpinista', 'has-error') }}">

                        <select id="state_id" name="state_id" class="form-control">
                            <option value="">Seleccione Departamento</option>   

                            @foreach($states as $state)

                                <option  @if ($state->id==$ciudad->state_id) {{'Selected'}} @endif value="{{ $state->id }}"> {{ $state->state_name}} </option>

                            @endforeach

                        </select>

                        {!! $errors->first('state_id', '<span class="help-block">:message</span>') !!}

                    </div>


                    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('city_id', 'has-error') }}">

                            <select id="city_id" name="city_id" class="form-control">

                                <option value="">Seleccione Ciudad</option>

                                @foreach($cities as $city)

                                    <option  @if ($city->id==$direccion->city_id) {{'Selected'}} @endif      value="{{ $city->id }}">{{ $city->city_name}}</option>

                                @endforeach

                            </select>

                        {!! $errors->first('city_id', '<span class="help-block">:message</span>') !!}

                    </div>


                    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('calle_address', 'has-error') }}">

                        <div class="col-sm-6" >
                            
                            <select id="id_estructura_address" name="id_estructura_address" class="form-control">

                                @foreach($estructura as $estru)

                                <option  @if ($estru->id==$direccion->id_estructura_address) {{'Selected'}} @endif    value="{{ $estru->id }}">{{ $estru->nombre_estructura}} </option>

                                @endforeach

                            </select>

                             {!! $errors->first('id_estructura_address', '<span class="help-block">:message</span>') !!}

                        </div>

                        <div class="col-sm-6">
                            
                            <input type="text" id="principal_address" name="principal_address" class="form-control" placeholder="Ejemplo: 44 " value="{!! old('principal_address', $direccion->principal_address) !!}" >

                            {!! $errors->first('principal_address', '<span class="help-block">:message</span>') !!}

                        </div>
                                    
                    </div>


                    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('calle_address', 'has-error') }}">

                        <div class="col-sm-6" >

                            <input type="text" id="secundaria_address" name="secundaria_address" placeholder="Ejemplo: #14 " class="form-control" value="{!! old('secundaria_address' ,$direccion->secundaria_address) !!}" >

                            {!! $errors->first('secundaria_address', '<span class="help-block">:message</span>') !!}

                        </div>

                        <div class="col-sm-6">

                            <input type="text" id="edificio_address" name="edificio_address" class="form-control" placeholder="Ejemplo: 100 " value="{!! old('edificio_address', $direccion->edificio_address) !!}" >

                            {!! $errors->first('edificio_address', '<span class="help-block">:message</span>') !!}

                        </div>

                    </div>

                    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('detalle_address', 'has-error') }}">

                        <input type="text" class="form-control" id="detalle_address" name="detalle_address" placeholder="Apto, Puerta, Interior" value="{!! old('detalle_address', $direccion->detalle_address) !!}" >

                        {!! $errors->first('detalle_address', '<span class="help-block">:message</span>') !!}

                    </div>

                    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('barrio_address', 'has-error') }}">

                        <input type="text" class="form-control" id="barrio_address" name="barrio_address" placeholder="Barrio" value="{!! old('barrio_address', $direccion->barrio_address) !!}" >

                        {!! $errors->first('barrio_address', '<span class="help-block">:message</span>') !!}

                    </div>

                    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('notas', 'has-error') }}">

                        <textarea style="margin: 4px 0;" id="notas" name="notas" type="text" placeholder="Notas" class="form-control" >{{$direccion->notas}}</textarea>

                        {!! $errors->first('notas', '<span class="help-block">:message</span>') !!}

                    </div>

                           
                    <div class="clearfix"></div>

                    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('notas', 'has-error') }}">

                        <button class="btn btn-primary" type="submit" >Editar </button>

                        <a class="btn btn-danger" href="{{secure_url('admin/clientes')}}">Volver</a>

                    </div>

                </form>


                    
                </div>
            </div>
        </div>
    </div>
    <!-- row-->

    <input type="hidden" name="base" id="base" value="{{secure_url('/')}}">
</section>
@stop

@section('footer_scripts')



<script>
    

  $(document).ready(function(){
        //Inicio select región
             

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
                                    $('select[name="city_id"]').append('<option value="'+ key +'">'+ value +'</option>');
                                });

                            }
                        });
                    }else{
                        $('select[name="city_id"]').empty();
                    }
                });
            //fin select ciudad
        });


  $("#addDireccionForm").bootstrapValidator({
    fields: {
        edit_principal_address: {
            validators: {
                notEmpty: {
                    message: 'Principal es Requerido'
                }
            },
            required: true,
            minlength: 3
        },
        edit_titulo: {
            validators: {
                notEmpty: {
                    message: 'Nombre de la dirección es Requerido'
                }
            },
            required: true,
            minlength: 3
        },
        
        edit_secundaria_address: {
            validators: {
                notEmpty: {
                    message: 'Calle Secundaria  es Requerido'
                    
                }
            },
            required: true,
            minlength: 3
        }, 
        edit_edificio_address: {
            validators: {
                notEmpty: {
                    message: 'Edificio  es Requerido'
                    
                }
            },
            required: true,
            minlength: 3
        },
         edit_detalle_address: {
            validators: {
                notEmpty: {
                    message: 'Detalle de la dirección  es Requerido'
                    
                }
            },
            required: true,
            minlength: 3
        },

        edit_barrio_address: {
            validators: {
                notEmpty: {
                    message: 'Barrio de la dirección  es Requerido'
                    
                }
            },
            required: true,
            minlength: 3
        },
        
        

        edit_city_id: {
            validators:{
                notEmpty:{
                    message: 'Debe seleccionar una ciudad'
                }
            }
        },

        edit_id_estructura_address: {
            validators:{
                notEmpty:{
                    message: 'Debe seleccionar una estructura'
                }
            }
        }


    }
});





</script>


@endsection


