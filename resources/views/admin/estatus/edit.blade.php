@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar Estatus Ordenes
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Editar Estatus Ordenes
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Estatus Ordenes</li>
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
                       Editar Estatus Ordenes
                    </h4>
                </div>
                <div class="panel-body">
                    
                        {!! Form::model($estatus, ['url' => URL::to('admin/estatus/'. $estatus->id), 'method' => 'put', 'class' => 'form-horizontal']) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}
                          
                             <div class="form-group {{ $errors->
                            first('estatus_nombre', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Nombre Estatus Ordenes
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="estatus_nombre" name="estatus_nombre" class="form-control" placeholder="Nombre de Estatus Ordenes"
                                       value="{!! old('estatus_nombre', $estatus->estatus_nombre) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('estatus_nombre', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('descripcion_estatus', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Descaripcion Estatus Ordenes
                            </label>
                            <div class="col-sm-5">
                                

                                <textarea class="form-control resize_vertical" id="descripcion_estatus" name="descripcion_estatus" placeholder="Descripcion Estatus Ordenes" rows="5">{!! old('descripcion_estatus', $estatus->descripcion_estatus) !!}</textarea>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('descripcion_estatus', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>
                       <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                <a class="btn btn-danger" href="{{ route('admin.estatus.index') }}">
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
