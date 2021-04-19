@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar Abonos
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Editar Abonos
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Abono</li>
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
                       Editar Abonos
                    </h4>
                </div>
                <div class="panel-body">
                    
                        {!! Form::model($abono, ['url' => secure_url('admin/abonos/'. $abono->id), 'method' => 'put', 'class' => 'form-horizontal']) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}
                          
                        <div class="form-group {{ $errors->
                            first('codigo_abono', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Codigo Abono
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="codigo_abono" name="codigo_abono" class="form-control" placeholder="Codigo Abono"
                                       value="{!! old('codigo_abono', $abono->codigo_abono) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('codigo_abono', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        <div class="form-group {{ $errors->
                            first('valor_abono', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Valor Abono
                            </label>
                            <div class="col-sm-5">
                                <input type="number" step="1" min="0" id="valor_abono" name="valor_abono" class="form-control" placeholder="Nombre de Sedes"
                                       value="{!! old('valor_abono', $abono->valor_abono) !!}">
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
                                       value="{!! old('fecha_final', $abono->fecha_final) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('fecha_final', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                       



                            <!-- ubicacion de la sede   -->


                       <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                
                                <a class="btn btn-danger" href="{{ route('admin.sedes.index') }}">
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



@stop



