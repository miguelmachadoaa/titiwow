@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar Transportistas
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Editar Transportistas
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Transportistas</li>
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
                       Editar Transportistas
                    </h4>
                </div>
                <div class="panel-body">
                    
                        {!! Form::model($transportistas, ['url' => secure_url('admin/transportistas/'. $transportistas->id), 'method' => 'put', 'class' => 'form-horizontal']) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}
                          
                             <div class="form-group {{ $errors->
                            first('nombre_transportista', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Nombre Transportistas
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="nombre_transportista" name="nombre_transportista" class="form-control" placeholder="Nombre de Transportistas"
                                       value="{!! old('nombre_transportista', $transportistas->nombre_transportista) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('transportistas_nombre', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('descripcion_transportista', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Descaripcion Transportistas
                            </label>
                            <div class="col-sm-5">
                                

                                <textarea class="form-control resize_vertical" id="descripcion_transportista" name="descripcion_transportista" placeholder="Descripcion Transportistas" rows="5">{!! old('descripcion_transportista', $transportistas->descripcion_transportista) !!}</textarea>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('descripcion_transportista', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>
                       <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                <a class="btn btn-danger" href="{{ route('admin.transportistas.index') }}">
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
