@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar Estatus Envios
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Editar Estatus Envios
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Estatus Envios</li>
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
                       Editar Estatus Envios
                    </h4>
                </div>
                <div class="panel-body">
                    
                        {!! Form::model($estatus, ['url' => secure_url('admin/estatusenvios/'. $estatus->id), 'method' => 'put', 'class' => 'form-horizontal']) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}
                          
                             <div class="form-group {{ $errors->
                            first('estatus_envio_nombre', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Nombre Estatus Envios
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="estatus_envio_nombre" name="estatus_envio_nombre" class="form-control" placeholder="Nombre de Estatus Envios"
                                       value="{!! old('estatus_envio_nombre', $estatus->estatus_envio_nombre) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('estatus_envio_nombre', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('estatus_envio_descripcion', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Descaripcion Estatus Envios
                            </label>
                            <div class="col-sm-5">
                                

                                <textarea class="form-control resize_vertical" id="estatus_envio_descripcion" name="estatus_envio_descripcion" placeholder="Descripcion Estatus Envios" rows="5">{!! old('estatus_envio_descripcion', $estatus->estatus_envio_descripcion) !!}</textarea>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('estatus_envio_descripcion', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                         <div class="form-group {{ $errors->
                            first('codigo', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Nombre Estatus Envios
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="codigo" name="codigo" class="form-control" placeholder="Nombre de Estatus Envios"
                                       value="{!! old('codigo', $estatus->codigo) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('codigo', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                       <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                
                                <a class="btn btn-danger" href="{{ route('admin.estatusenvios.index') }}">
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
