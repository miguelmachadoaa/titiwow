@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
    Empresa
    @parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Empresa
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Empresa</li>
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
                       Crear Empresa
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
                    <form class="form-horizontal" role="form" method="post" action="{{ secure_url('admin/empresas/create') }}">
                        <!-- CSRF Token -->

                        {{ csrf_field() }}

                        <div class="form-group {{ $errors->
                            first('nombre_empresa', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Nombre  Empresa
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="nombre_empresa" name="nombre_empresa" class="form-control" placeholder="Nombre de Empresa"
                                       value="{!! old('nombre_empresa') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('nombre_empresa', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('descripcion_empresa', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Descripcion Empresa
                            </label>
                            <div class="col-sm-5">
                                

                                <textarea class="form-control resize_vertical" id="descripcion_empresa" name="descripcion_empresa" placeholder="Descripcion Empresa" rows="5">{!! old('descripcion_empresa') !!}</textarea>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('descripcion_empresa', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('descuento_empresa', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Descuento  Empresa
                            </label>
                            <div class="col-sm-5">
                                <input type="number" min="0" max="100" step="0.01" id="descuento_empresa" name="descuento_empresa" class="form-control" placeholder="Nombre de Empresa"
                                       value="{!! old('descuento_empresa') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('descuento_empresa', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>





                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                <button type="submit" class="btn btn-success">
                                    Crear
                                </button>
                                <a class="btn btn-danger" href="{{ secure_url('admin/empresas') }}">
                                    Cancelar
                                </a>
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
