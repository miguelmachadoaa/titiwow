@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
    Almacen
    @parent
@stop

@section('header_styles')

   <link href="{{ secure_asset('assets/vendors/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />

    <link href="{{ secure_asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ secure_asset('assets/css/pages/blog.css') }}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}">

@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Almacen
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Almacen</li>
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
                       Crear Almacen
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
                    <form enctype="multipart/form-data" class="form-horizontal" role="form" method="POST" action="{{ secure_url('admin/almacenes/create') }}">
                        <!-- CSRF Token -->

                        {{ csrf_field() }}

                        <div class="form-group {{ $errors->
                            first('nombre_almacen', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Nombre  Almacen
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="nombre_almacen" name="nombre_almacen" class="form-control" placeholder="Nombre de Almacen"
                                       value="{!! old('nombre_almacen') !!}">
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
                                

                                <textarea class="form-control resize_vertical" id="descripcion_almacen" name="descripcion_almacen" placeholder="Descripción Almacen" rows="5">{!! old('descripcion_almacen') !!}</textarea>
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

                                                        <option value="{{ $state->id }}">
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
                                
                                 <select id="city_id" name="city_id" class="form-control select2">
                                                        <option value="">Seleccione</option>
                                                      
                                                    </select>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('city_id', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                         <div class="form-group {{ $errors->
                            first('minimo_compra', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                               Minimo de Compra para almacen
                            </label>
                            <div class="col-sm-5">
                                
                               <input type="number" step="0.01" min="0"  id="minimo_compra" name="minimo_compra" class="form-control" placeholder="Minimo Compra"
                                            value="{!! old('minimo_compra') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('minimo_compra', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>





                        <div class="form-group {{ $errors->
                            first('correos', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                               Correos de Envio de Reporte <small>Separar con ;</small>
                            </label>
                            <div class="col-sm-5">
                                
                               <input style="margin: 4px 0;" id="correos" name="correos" type="text" placeholder="Correos de Envio" value="{!! old('correos') !!}" class="form-control">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('correos', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>





                        <div class="form-group {{ $errors->
                            first('hora', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                               Hora para envio de reporte
                            </label>
                            <div class="col-sm-5">
                                
                               <input style="margin: 4px 0;" id="hora" name="hora" type="text" placeholder="Hora de envio reporte" value="{!! old('hora') !!}" class="form-control">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('hora', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>



                         <div class="form-group {{ $errors->
                            first('defecto', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                               Tipo de Almacen
                            </label>
                            <div class="col-sm-5">
                                
                                <select id="tipo_almacen" name="tipo_almacen" class="form-control select2">
                                    <option  value="0">Normal</option>
                                    <option  value="1">Nomina</option>
                                    
                                                      
                                </select>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('tipo_almacen', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>




                         <div class="form-group {{ $errors->
                            first('defecto', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Almacen por Defecto 
                            </label>
                            <div class="col-sm-5">
                                
                                <select id="defecto" name="defecto" class="form-control select2">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                    
                                                      
                                </select>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('defecto', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>






                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                               
                                <a class="btn btn-danger" href="{{ secure_url('admin/almacenes') }}">
                                    Cancelar
                                </a>

                                 <button type="submit" class="btn btn-success">
                                    Crear
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- row-->

    <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">


</section>
@stop
@section('footer_scripts')


<script src="{{ secure_asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>
 <script src="{{ secure_asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ secure_asset('assets/vendors/daterangepicker/js/daterangepicker.js') }}" type="text/javascript"></script>

<script src="{{ secure_asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>

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


       $("#hora").datetimepicker({
    format: 'HH:mm'
}).parent().css("position :relative");

    $('body').on('click', '.delciudad', function(){

            $('#del_id').val($(this).data('id'));

            $("#delCiudadModal").modal('show');

        });





</script>

@stop
