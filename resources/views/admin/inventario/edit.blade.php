@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Agregar Inventario
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Agregar Inventario
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Agregar Inventario</li>

        <li class="active">Agregar Inventario</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Agregar Inventario
                    </h4>
                </div>
                <div class="panel-body">
                    
                        {!! Form::model($producto, ['url' => secure_url('admin/inventario/'. $producto->id), 'method' => 'put', 'class' => 'form-horizontal']) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}
                          
                             <div class="form-group {{ $errors->
                            first('cantidad', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Cantidad
                            </label>
                            <div class="col-sm-5">
                                <input required="true" type="text" id="cantidad" name="cantidad" class="form-control" placeholder="Cantidad"
                                       value="{!! old('cantidad') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('cantidad', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                       <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                <a class="btn btn-danger" href="{{ route('admin.inventario.index') }}">
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
