@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
    Impuestos
    @parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
       Impuestos
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Impuestos</li>
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
                       Crear Impuestos
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
                    <form class="form-horizontal" role="form" method="post" action="{{ secure_url('admin/impuestos/create') }}">
                        <!-- CSRF Token -->

                        {{ csrf_field() }}

                        <div class="form-group {{ $errors->
                            first('nombre_impuesto', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Nombre 
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="nombre_impuesto" name="nombre_impuesto" class="form-control" placeholder="Nombre de Impuestos"
                                       value="{!! old('nombre_impuesto') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('nombre_impuesto', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('valor_impuesto', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Valor del Impuesto 
                            </label>
                           <div class="col-sm-5">
                                <input type="number" step="0.01" id="valor_impuesto" name="valor_impuesto" class="form-control" placeholder="Valor del impuesto"
                                       value="{!! old('valor_impuesto') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('valor_impuesto', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>





                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                <button type="submit" class="btn btn-success">
                                    Crear
                                </button>
                                <a class="btn btn-danger" href="{{ secure_url('admin./impuestos') }}">
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
