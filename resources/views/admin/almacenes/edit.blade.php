@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar Almacen
@parent
@stop

@section('header_styles')

    <link href="{{ secure_asset('assets/vendors/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />

    <link href="{{ secure_asset('assets/css/pages/blog.css') }}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}">

@stop


{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Editar Almacen
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Almacens</li>
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
                       Editar Almacen
                    </h4>
                </div>
                <div class="panel-body">
                    
                        {!! Form::model($almacen, ['url' => secure_url('admin/almacenes/'. $almacen->id), 'method' => 'PUT', 'class' => 'form-horizontal', 'files'=> true]) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}

                            <input type="hidden" name="id_almacen" id="id_almacen" value="{{$almacen->id}}">
                          
                       
                        <div class="form-group {{ $errors->
                            first('nombre_almacen', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Nombre  Almacen
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="nombre_almacen" name="nombre_almacen" class="form-control" placeholder="Nombre de Almacen"
                                       value="{!! old('nombre_almacen', $almacen->nombre_almacen) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('nombre_almacen', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('descripcion_almacen', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Descripción Almacen
                            </label>
                            <div class="col-sm-5">
                                

                                <textarea class="form-control resize_vertical" id="descripcion_almacen" name="descripcion_almacen" placeholder="Descripción Almacen" rows="5">{!! old('descripcion_almacen', $almacen->descripcion_almacen) !!}</textarea>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('descripcion_almacen', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                         <div class="form-group {{ $errors->
                            first('state_id', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Departamento
                            </label>
                            <div class="col-sm-5">
                                
                                 <select id="state_id" name="state_id" class="form-control select2">

                                    <option value="">Seleccione</option>
                                    
                                    @foreach($states as $state)

                                    <option @if($city->state_id==$state->id) {{'Selected'}} @endif  value="{{ $state->id }}">

                                            {{ $state->state_name}}</option>

                                    @endforeach
                                                        
                                                      
                                                    </select>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('state_id', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        <div class="form-group {{ $errors->
                            first('city_id', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Ciudad
                            </label>
                            <div class="col-sm-5">
                                
                                 <select  id="city_id" name="city_id" class="form-control select2">
                                    
                                    <option value="">Seleccione</option>


                                    @foreach($cities as $c)

                                        <option @if($almacen->id_city==$c->id) {{'Selected'}} @endif  value="{{ $c->id }}"> {{ $c->city_name}}</option>

                                    @endforeach
                                  
                                </select>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('city_id', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                         <div class="form-group {{ $errors->
                            first('defecto', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Almacen por Defecto 
                            </label>
                            <div class="col-sm-5">
                                
                                <select id="defecto" name="defecto" class="form-control select2">
                                    <option @if($almacen->defecto=='1') {{'Selected'}} @endif value="1">Si</option>
                                    <option @if($almacen->defecto=='0') {{'Selected'}} @endif value="0">No</option>
                                                      
                                </select>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('defecto', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                       <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                
                                <a class="btn btn-danger" href="{{ route('admin.almacenes.index') }}">
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

    <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">



    <!-- row-->
</section>

@stop
@section('footer_scripts')


<script src="{{ secure_asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>

<script type="text/javascript" src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>




<script>
    

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



</script>


@stop