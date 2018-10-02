@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar Configuracion
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Editar Configuracion
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Configuracions</li>
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
                       Editar Configuracion
                    </h4>
                </div>
                <div class="panel-body">
                    
                        {!! Form::model($configuracion, ['url' => URL::to('admin/configuracion/'. $configuracion->id), 'method' => 'put', 'class' => 'form-horizontal']) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}
                          
                            <div class="form-group {{ $errors->first('nombre_tienda', 'has-error') }}">
                                <label for="title" class="col-sm-2 control-label">
                                    Nombre Tienda
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="nombre_tienda" name="nombre_tienda" class="form-control" placeholder="Nombre Tienda"
                                        value="{!! old('nombre_tienda', $configuracion->nombre_tienda) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('nombre_tienda', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>
                            <hr />
                            <div class="form-group {{ $errors->first('limite_amigos', 'has-error') }}">
                                <label for="title" class="col-sm-2 control-label">
                                    Limite de Amigos Alpina
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="limite_amigos" name="limite_amigos" class="form-control" placeholder="Limite de Amigos Alpina"
                                        value="{!! old('limite_amigos', $configuracion->limite_amigos) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('limite_amigos', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>
                            <hr />
                            <div class="form-group {{ $errors->first('id_mercadopago', 'has-error') }}">
                                <label for="title" class="col-sm-2 control-label">
                                    ID Mercadopago
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="id_mercadopago" name="id_mercadopago" class="form-control" placeholder="ID Mercadopago"
                                        value="{!! old('id_mercadopago', $configuracion->id_mercadopago) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('id_mercadopago', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('key_mercadopago', 'has-error') }}">
                                <label for="title" class="col-sm-2 control-label">
                                    Key Mercadopago
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="key_mercadopago" name="key_mercadopago" class="form-control" placeholder="Key Mercadopago"
                                        value="{!! old('key_mercadopago', $configuracion->key_mercadopago) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('key_mercadopago', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>
                      
                       <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                <a class="btn btn-danger" href="{{ route('admin.configuracion.index') }}">
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
