@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
    Lifemiles
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
        Lifemiles
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Lifemiles</li>
        <li class="active">
            Editar
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
                       Editar Campaña Lifemile
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
                    {!! Form::model($lifemile, ['url' => secure_url('admin/lifemiles/'. $lifemile->id), 'method' => 'put', 'class' => 'form-horizontal']) !!}
                 
                        <!-- CSRF Token -->

                        {{ csrf_field() }}

                        <div class="form-group {{ $errors->
                            first('nombre_lifemile', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Nombre Campaña Lifemiles 
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="nombre_lifemile" name="nombre_lifemile" class="form-control" placeholder="Nombre Campaña Lifemiles "
                                value="{!! old('nombre_lifemile', $lifemile->nombre_lifemile) !!}">


                            </div>
                            <div class="col-sm-4">
                               
                                {!! $errors->first('nombre_lifemile', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        <div class="form-group {{ $errors->
                            first('cantidad_millas', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Cantidad Millas
                            </label>
                            <div class="col-sm-5">
                                <input type="number" step="1" min="0" id="cantidad_millas" name="cantidad_millas" class="form-control" placeholder="Cantidad Millas"
                                value="{!! old('cantidad_millas', $lifemile->cantidad_millas) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('cantidad_millas', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('minimo_compra', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Minimo Compra
                            </label>
                            <div class="col-sm-5">
                                <input type="number" step="1" min="0" id="minimo_compra" name="minimo_compra" class="form-control" placeholder=" Minimo Compra"
                                value="{!! old('minimo_compra', $lifemile->minimo_compra) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('minimo_compra', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        <div class="form-group {{ $errors->
                            first('fecha_inicio', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Fecha Inicio
                            </label>
                            <div class="col-sm-5">
                                <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" placeholder="Fecha Inicio"  value="{!! old('fecha_inicio', $lifemile->fecha_inicio) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('fecha_inicio', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        <div class="form-group {{ $errors->
                            first('fecha_final', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Fecha Final
                            </label>
                            <div class="col-sm-5">
                                <input type="date" id="fecha_final" name="fecha_final" class="form-control" placeholder="Fecha Final"  value="{!! old('fecha_final', $lifemile->fecha_final) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('fecha_final', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>



                        <div class="form-group {{ $errors->
                            first('id_almacen', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Seleccione Almacen 
                            </label>
                            <div class="col-sm-5">
                                
                                 <select id="id_almacen" name="id_almacen" class="form-control select2">

                                    <option value="">Seleccione</option>
                                    <option value="0">Todos</option>
                                    
                                    @foreach($almacenes as $a)

                                    <option @if($lifemile->id_almacen==$a->id) {{'Selected'}} @endif value="{{ $a->id }}">
                                            {{ $a->nombre_almacen}}</option>
                                    @endforeach
                                    
                                  
                                </select>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('id_almacen', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                            <!-- ubicacion de la sede   -->


                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                
                                <a class="btn btn-danger" href="{{ secure_url('admin/lifemiles') }}">
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

<script src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>


<script type="text/javascript">
    

   
    $(document).ready(function(){

            $('.select2').select2({
                placeholder: "Seleccionar",
                theme:"bootstrap"
            });

            var someDateInicio = new Date();
            var someDate = new Date();

            var duration = '30'; //In Days

            someDate.setTime(someDate.getTime() +  (duration * 24 * 60 * 60 * 1000));

            day=someDate.getDate();

            month=someDate.getMonth();

            year=someDate.getFullYear();

            $('#fecha_inicio').val(someDateInicio.toISOString().substr(0, 10));
            $('#fecha_final').val(someDate.toISOString().substr(0, 10));

          //  console.log($('#fecha_final').val());

    });



</script>

@stop
