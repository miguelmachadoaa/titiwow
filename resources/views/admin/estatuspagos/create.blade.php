@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
    Estatus Pagos
    @parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
       Estatus Pagos
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Estatus Pagos</li>
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
                       Crear Estatus Pagos
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
                    <form class="form-horizontal" role="form" method="post" action="{{ secure_url('admin/estatuspagos/create') }}">
                        <!-- CSRF Token -->

                        {{ csrf_field() }}

                        <div class="form-group {{ $errors->
                            first('estatus_pago_nombre', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Nombre 
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="estatus_pago_nombre" name="estatus_pago_nombre" class="form-control" placeholder="Nombre de Estatus"
                                       value="{!! old('estatus_pago_nombre') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('estatus_pago_nombre', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('estatus_pago_descripcion', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Descripcion 
                            </label>
                            <div class="col-sm-5">
                                

                                <textarea class="form-control resize_vertical" id="estatus_pago_descripcion" name="estatus_pago_descripcion" placeholder="Descripcion Estatus de Envio" rows="5">{!! old('estatus_pago_descripcion') !!}</textarea>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('estatus_pago_descripcion', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>





                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                
                                <a class="btn btn-danger" href="{{ secure_url('admin/estatuspagos') }}">
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
