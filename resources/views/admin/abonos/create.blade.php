@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
    Abono
    @parent
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

<script type="text/javascript">
    

   


    $('#codigo_abono_generar').click(function(){

        caracteres = "0123456789ABCDEF";
        longitud = 8;

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
