@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
    Abono
    @parent
@stop


@section('header_styles')
    

    <link href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />

    <link href="{{ secure_asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" />
    
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Abono
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Abono</li>
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
                       Crear Abono
                    </h4>
                </div>
                <div class="panel-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form class="form-horizontal" role="form" method="post" action="{{ secure_url('admin/abonos/create') }}">
                        <!-- CSRF Token -->

                        {{ csrf_field() }}

                           
                        <div class="form-group {{ $errors->
                            first('codigo_abono', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Codigo Abono 
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="codigo_abono" name="codigo_abono" class="form-control" placeholder="Codigo Abono"
                                       value="{!! old('codigo_abono') !!}">

                                       


                            </div>
                            <div class="col-sm-4">
                                <button type="button" class="btn btn-link btn-xs" id="codigo_abono_generar">Generar</button>
                                {!! $errors->first('codigo_abono', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        <div class="form-group {{ $errors->
                            first('valor_abono', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Valor Abono
                            </label>
                            <div class="col-sm-5">
                                <input type="number" step="1" min="0" id="valor_abono" name="valor_abono" class="form-control" placeholder="Valor del Abono"
                                       value="{!! old('valor_abono') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('valor_abono', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        <div class="form-group {{ $errors->
                            first('tipo_abono', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Tipo de Bono
                            </label>
                            <div class="col-sm-5">
                                
                                 <select id="tipo_abono" name="tipo_abono" class="form-control select2">

                                    <option value="">Seleccione</option>
                                    
                                    @foreach($tipobono as $ta)

                                    <option value="{{ $ta->id }}">
                                            {{ $ta->nombre_tipo}}</option>
                                    @endforeach
                                    
                                  
                                </select>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('tipo_abono', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>






                        <div class="form-group {{ $errors->
                            first('fecha_final', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Fecha Limite
                            </label>
                            <div class="col-sm-5">
                                <input type="date" id="fecha_final" name="fecha_final" class="form-control" placeholder="Nombre de Sedes"
                                       value="{!! old('fecha_final') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('fecha_final', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        <div class="form-group {{ $errors->
                            first('motivo', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Motivo
                            </label>
                            <div class="col-sm-5">
                                

                                <textarea class="form-control resize_vertical" id="motivo" name="motivo" placeholder="Motivo del Bono" rows="5">{!! old('motivo') !!}</textarea>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('motivo', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        <div class="form-group {{ $errors->
                            first('id_cliente', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Asignar Bono a Cliente <small>Opcional</small>
                            </label>
                            <div class="col-sm-5">
                                
                                 <select id="id_cliente" name="id_cliente" class="form-control select2">

                                    <option value="">Seleccione</option>
                                    
                                    @foreach($clientes as $c)

                                    <option value="{{ $c->id }}">
                                            {{ $c->first_name.' '.$c->last_name.' - '.$c->email}}</option>
                                    @endforeach
                                    
                                  
                                </select>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('id_cliente', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('id_orden', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Seleccionar Orden Motivo del bono <small>Opcional</small>
                            </label>
                            <div class="col-sm-5">
                                
                                 <select id="id_orden" name="id_orden" class="form-control select2">

                                    <option value="">Seleccione</option>
                                    
                                    @foreach($ordenes as $o)

                                    <option value="{{ $o->id }}">
                                            {{ $o->referencia}}</option>
                                    @endforeach
                                    
                                  
                                </select>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('id_orden', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>




                        <div class="form-group {{ $errors->
                            first('notas', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Notas
                            </label>
                            <div class="col-sm-5">
                                

                                <textarea class="form-control resize_vertical" id="notas" name="notas" placeholder="Notas del Bono" rows="5">{!! old('notas') !!}</textarea>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('notas', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>





                            <!-- ubicacion de la sede   -->


                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                
                                <a class="btn btn-danger" href="{{ secure_url('admin/abonos') }}">
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
</section>
@stop

@section('footer_scripts')

<script src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>


<script type="text/javascript">
    

   
    $(document).ready(function(){

            $('.select2').select2({
                placeholder: "Seleccionar",
                theme:"bootstrap"
            });
    });

    $('#codigo_abono_generar').click(function(){

        caracteres = "0123456789ABCDEF";
        longitud = 12;

        code='';

        for (x=0; x < longitud; x++)
        {
            rand = Math.floor(Math.random()*caracteres.length);
            code += caracteres.substr(rand, 1);
        }


        $('#codigo_abono').val(code);


    });



</script>

@stop
