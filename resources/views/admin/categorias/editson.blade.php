@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar Categoria
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Editar Categoria
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Categorias</li>
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
                       Editar Categoria
                    </h4>
                </div>
                <div class="panel-body">
                    
                        {!! Form::model($categoria, ['url' => URL::to('admin/categorias/'. $categoria->id.'/updson'), 'method' => 'post', 'class' => 'form-horizontal']) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}

                            <input type="hidden" name="id_categoria_parent" id="id_categoria_parent" value="{{$categoria->id_categoria_parent}}">
                          
                             <div class="form-group {{ $errors->
                            first('nombre_categoria', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Nombre Categoria
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="nombre_categoria" name="nombre_categoria" class="form-control" placeholder="Nombre de Categoria"
                                       value="{!! old('nombre_categoria', $categoria->nombre_categoria) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('nombre_categoria', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('descripcion_categoria', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Descaripcion Categoria
                            </label>
                            <div class="col-sm-5">
                                

                                <textarea class="form-control resize_vertical" id="descripcion_categoria" name="descripcion_categoria" placeholder="Descripcion categoria" rows="5">{!! old('descripcion_categoria', $categoria->descripcion_categoria) !!}</textarea>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('descripcion_categoria', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>
                       <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                <a class="btn btn-danger" href="{{ route('admin.categorias.index') }}">
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
