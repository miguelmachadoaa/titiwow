@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar Empresa
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Editar Empresa
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Empresas</li>
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
                       Editar Empresa
                    </h4>
                </div>
                <div class="panel-body">
                    
                        {!! Form::model($empresas, ['url' => URL::to('admin/empresas/'. $empresas->id), 'method' => 'put', 'class' => 'form-horizontal']) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}
                          
                        <div class="form-group {{ $errors->
                            first('nombre_empresa', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Nombre Empresa
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="nombre_empresa" name="nombre_empresa" class="form-control" placeholder="Nombre de Empresa"
                                       value="{!! old('nombre_empresa', $empresas->nombre_empresa) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('nombre_empresa', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('descripcion_empresa', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Descripción Empresa
                            </label>
                            <div class="col-sm-5">
                                

                                <textarea class="form-control resize_vertical" id="descripcion_empresa" name="descripcion_empresa" placeholder="Descripcion Empresa" rows="5">{!! old('descripcion_empresa', $empresas->descripcion_empresa) !!}</textarea>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('descripcion_empresa', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('descuento_empresa', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Descuento Empresa
                            </label>
                            <div class="col-sm-5">
                                <input type="number" min="0" step="0.01" max="100" id="descuento_empresa" name="descuento_empresa" class="form-control" placeholder="Nombre de Empresa"
                                       value="{!! old('descuento_empresa', $empresas->descuento_empresa) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('descuento_empresa', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                       <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                <a class="btn btn-danger" href="{{ route('admin.empresas.index') }}">
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
