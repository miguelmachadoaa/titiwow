@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
    Formas de Pago
    @parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Formas de Pago
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Formas de Pago</li>
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
                       Crear Forma de Pago
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
                    <form class="form-horizontal" role="form" method="post" action="{{ secure_url('admin/formaspago/create') }}">
                        <!-- CSRF Token -->

                        {{ csrf_field() }}

                        <div class="form-group {{ $errors->
                            first('nombre_forma_pago', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Nombre 
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="nombre_forma_pago" name="nombre_forma_pago" class="form-control" placeholder="Nombre de Forma de Pago"
                                       value="{!! old('nombre_forma_pago') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('nombre_forma_pago', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('descripcion_forma_pago', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Descripcion 
                            </label>
                            <div class="col-sm-5">
                                

                                <textarea class="form-control resize_vertical" id="descripcion_forma_pago" name="descripcion_forma_pago" placeholder="Descripcion Forma de pago" rows="5">{!! old('descripcion_forma_pago') !!}</textarea>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('descripcion_forma_pago', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('orden', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Orden 
                            </label>
                            <div class="col-sm-5">
                                <input type="number" min="1" step="1"  id="orden" name="orden" class="form-control" placeholder="Orden de Forma de Pago"
                                       value="{!! old('orden') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('orden', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                         <div class="form-group  {{ $errors->first('vuelto', 'has-error') }}">
                                <label for="select21" class="col-sm-2 control-label">
                                    Se usa para dar vuelto
                                </label>
                                <div class="col-sm-5">   
                                 <select id="vuelto" name="vuelto" class="form-control ">
                                    <option value="">Seleccione</option>
                                        
                                       
                                        <option value="{{ 0 }}"
                                                >Desactivado</option>

                                        <option value="{{ 1 }}"
                                                >Activado</option>
                                       
                                </select>
                                <div class="col-sm-4">
                                    {!! $errors->first('vuelto', '<span class="help-block">:message</span> ') !!}
                                </div>
                                  
                                </div>
                               
                            </div>







                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                <a class="btn btn-danger" href="{{ route('admin.formaspago.index') }}">
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
