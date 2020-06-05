@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar Almacen
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
                                    <option value="0" @if($almacen->id_state=='0') {{'Selected'}} @endif >Todos</option>
                                    
                                    @foreach($states as $state)

                                    <option @if($almacen->id_state==$state->id) {{'Selected'}} @endif  value="{{ $state->id }}">

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

                                    <option value="0" @if($almacen->id_city=='0') {{'Selected'}} @endif >Todos</option>



                    
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
                            first('minimo_compra', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                               Minimo de Compra para almacen
                            </label>
                            <div class="col-sm-5">
                                
                               <input type="number" step="0.01" min="0"  id="minimo_compra" name="minimo_compra" class="form-control" placeholder="Minimo Compra"
                                            value="{!! old('minimo_compra', $almacen->minimo_compra) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('minimo_compra', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        <div class="form-group {{ $errors->
                            first('hora', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                               Hora para envio de reporte
                            </label>
                            <div class="col-sm-5">
                                
                               <input style="margin: 4px 0;" id="hora" name="hora" type="text" placeholder="Hora de envio reporte" value="{!! old('hora', $almacen->hora) !!}" class="form-control">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('hora', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        
                        <div class="form-group {{ $errors->
                            first('correos', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                               Correos de Envio de Reporte <small>Separar con ;</small>
                            </label>
                            <div class="col-sm-5">
                                
                               <input style="margin: 4px 0;" id="correos" name="correos" type="text" placeholder="Correos de Envio" value="{!! old('correos', $almacen->correos) !!}" class="form-control">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('correos', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        <div class="form-group {{ $errors->
                            first('descuento_productos', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                               Mostrar descuento en productos
                            </label>
                            <div class="col-sm-5">
                                
                                <select id="descuento_productos" name="descuento_productos" class="form-control select2">
                                    <option @if($almacen->descuento_productos=='0') {{'Selected'}} @endif value="0">No Mostrar </option>
                                    <option @if($almacen->descuento_productos=='1') {{'Selected'}} @endif value="1">Mostrar</option>
                                    
                                                      
                                </select>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('descuento_productos', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>



                        <div class="form-group {{ $errors->
                            first('defecto', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                               Tipo de Almacen
                            </label>
                            <div class="col-sm-5">
                                
                                <select id="tipo_almacen" name="tipo_almacen" class="form-control select2">
                                    <option @if($almacen->tipo_almacen=='0') {{'Selected'}} @endif value="0">Normal</option>
                                    <option @if($almacen->tipo_almacen=='1') {{'Selected'}} @endif value="1">Nomina</option>
                                    
                                                      
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
                                    <option @if($almacen->defecto=='0') {{'Selected'}} @endif value="0">No</option>
                                    <option @if($almacen->defecto=='1') {{'Selected'}} @endif value="1">Si</option>
                                    
                                                      
                                </select>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('defecto', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>



                        <fieldset>
                            <h3>Dirección del Almacen</h3>


                            @if(isset($direccion->id))

                            <input type="hidden" class="form-control" id="titulo" name="titulo" placeholder="Nombre para esta dirección" value="Direccion de Almacen" >

                     
                        <div style="  margin-bottom: 1em;" class=" col-sm-7  {{ $errors->first('calle_address', 'has-error') }}">

                            <div class="col-sm-6" >
                                
                                <select id="id_estructura_address" name="id_estructura_address" class="form-control">

                                    @foreach($estructura as $estru)

                                    <option @if($direccion->id_estructura_address==$estru->id)  {{'Selected'}} @endif value="{{ $estru->id }}">{{ $estru->nombre_estructura}} </option>

                                    @endforeach

                                </select>

                                 {!! $errors->first('id_estructura_address', '<span class="help-block">:message</span>') !!}

                            </div>

                            <div class="col-sm-6">
                                
                                <input type="text" id="principal_address" name="principal_address" class="form-control" placeholder="Ejemplo: 44 " value="{!! old('principal_address', $direccion->principal_address) !!}" >

                                {!! $errors->first('principal_address', '<span class="help-block">:message</span>') !!}

                            </div>
                                        
                        </div>


                        <div style="  margin-bottom: 1em;" class=" col-sm-7  {{ $errors->first('calle_address', 'has-error') }}">

                            <div class="col-sm-6" >

                                <input type="text" id="secundaria_address" name="secundaria_address" placeholder="Ejemplo: #14 " class="form-control" value="{!! old('secundaria_address',  $direccion->secundaria_address) !!}" >

                                {!! $errors->first('secundaria_address', '<span class="help-block">:message</span>') !!}

                            </div>

                            <div class="col-sm-6">

                                <input type="text" id="edificio_address" name="edificio_address" class="form-control" placeholder="Ejemplo: 100 " value="{!! old('edificio_address',  $direccion->edificio_address) !!}" >

                                {!! $errors->first('edificio_address', '<span class="help-block">:message</span>') !!}

                            </div>

                        </div>

                        <div style="  margin-bottom: 1em;" class=" col-sm-7  {{ $errors->first('detalle_address', 'has-error') }}">

                            <input type="text" class="form-control" id="detalle_address" name="detalle_address" placeholder="Apto, Puerta, Interior" value="{!! old('detalle_address',   $direccion->detalle_address) !!}" >

                            {!! $errors->first('detalle_address', '<span class="help-block">:message</span>') !!}

                        </div>

                        <div style="  margin-bottom: 1em;" class=" col-sm-7  barrio_address {{ $errors->first('barrio_address', 'has-error') }}">

                            <input type="text" class="form-control" id="barrio_address" name="barrio_address" placeholder="Barrio" value="{!! old('barrio_address',   $direccion->barrio_address) !!}" >

                            {!! $errors->first('barrio_address', '<span class="help-block">:message</span>') !!}

                        </div>

                         <div style="margin-left: 8%;" class="form-group col-sm-7  id_barrio {{ $errors->first('id_barrio', 'has-error') }} hidden">
                            <div class="" >
                                <select id="id_barrio" name="id_barrio" value="{!! old('id_barrio',   $direccion->id_barrio) !!}" class="form-control">
                                    <option value="">Seleccione Barrio</option>
                                </select>
                            </div>
                            {!! $errors->first('id_barrio', '<span class="help-block">:message</span>') !!}
                        </div>



                        <div style="  margin-bottom: 1em;" class=" col-sm-7  {{ $errors->first('notas', 'has-error') }}">

                            <textarea style="margin: 4px 0;" id="notas" name="notas" type="text" placeholder="Notas" class="form-control" >{{$direccion->notas}}</textarea>

                            {!! $errors->first('notas', '<span class="help-block">:message</span>') !!}

                        </div>


                        @else


                             <input type="hidden" class="form-control" id="titulo" name="titulo" placeholder="Nombre para esta dirección" value="Direccion de Almacen" >

                     
                        <div style="  margin-bottom: 1em;" class=" col-sm-7  {{ $errors->first('calle_address', 'has-error') }}">

                            <div class="col-sm-6" >
                                
                                <select id="id_estructura_address" name="id_estructura_address" class="form-control">

                                    @foreach($estructura as $estru)

                                    <option value="{{ $estru->id }}">{{ $estru->nombre_estructura}} </option>

                                    @endforeach

                                </select>

                                 {!! $errors->first('id_estructura_address', '<span class="help-block">:message</span>') !!}

                            </div>

                            <div class="col-sm-6">
                                
                                <input type="text" id="principal_address" name="principal_address" class="form-control" placeholder="Ejemplo: 44 " value="{!! old('principal_address') !!}" >

                                {!! $errors->first('principal_address', '<span class="help-block">:message</span>') !!}

                            </div>
                                        
                        </div>


                        <div style="  margin-bottom: 1em;" class=" col-sm-7  {{ $errors->first('calle_address', 'has-error') }}">

                            <div class="col-sm-6" >

                                <input type="text" id="secundaria_address" name="secundaria_address" placeholder="Ejemplo: #14 " class="form-control" value="{!! old('secundaria_address') !!}" >

                                {!! $errors->first('secundaria_address', '<span class="help-block">:message</span>') !!}

                            </div>

                            <div class="col-sm-6">

                                <input type="text" id="edificio_address" name="edificio_address" class="form-control" placeholder="Ejemplo: 100 " value="{!! old('edificio_address') !!}" >

                                {!! $errors->first('edificio_address', '<span class="help-block">:message</span>') !!}

                            </div>

                        </div>

                        <div style="  margin-bottom: 1em;" class=" col-sm-7  {{ $errors->first('detalle_address', 'has-error') }}">

                            <input type="text" class="form-control" id="detalle_address" name="detalle_address" placeholder="Apto, Puerta, Interior" value="{!! old('detalle_address') !!}" >

                            {!! $errors->first('detalle_address', '<span class="help-block">:message</span>') !!}

                        </div>

                        <div style="  margin-bottom: 1em;" class=" col-sm-7  barrio_address {{ $errors->first('barrio_address', 'has-error') }}">

                            <input type="text" class="form-control" id="barrio_address" name="barrio_address" placeholder="Barrio" value="{!! old('barrio_address') !!}" >

                            {!! $errors->first('barrio_address', '<span class="help-block">:message</span>') !!}

                        </div>

                         <div style="margin-left: 8%;" class="form-group col-sm-7  id_barrio {{ $errors->first('id_barrio', 'has-error') }} hidden">
                            <div class="" >
                                <select id="id_barrio" name="id_barrio" value="{!! old('id_barrio') !!}" class="form-control">
                                    <option value="">Seleccione Barrio</option>
                                </select>
                            </div>
                            {!! $errors->first('id_barrio', '<span class="help-block">:message</span>') !!}
                        </div>



                        <div style="  margin-bottom: 1em;" class=" col-sm-7  {{ $errors->first('notas', 'has-error') }}">

                            <textarea style="margin: 4px 0;" id="notas" name="notas" type="text" placeholder="Notas" class="form-control" ></textarea>

                            {!! $errors->first('notas', '<span class="help-block">:message</span>') !!}

                        </div>



                        @endif



                        </fieldset>






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
                            url: base+'/configuracion/citiestodos/'+stateID,
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