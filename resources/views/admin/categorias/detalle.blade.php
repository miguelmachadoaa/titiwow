@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Categoria {{$categoria->id}}
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Ver Categoria {{$categoria->id}}
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Categorias</li>
        <li class="active">Ver</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Agregar Sub Categoria a  Categoria {{$categoria->nombre_categoria}}
                    </h4>
                </div>
                <div class="panel-body">
                    
                        <form class="form-horizontal" role="form" method="post" action="{{ url('admin/categorias/'.$categoria->id.'/storeson') }}">
                            <!-- CSRF Token -->
                            {{ csrf_field() }}

                             <input type="hidden" name="id_categoria_padre" id="id_categoria_padre" value="{{$categoria->id}}">
                          
                             <div class="form-group {{ $errors->
                            first('nombre_categoria', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Nombre Categoria
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="nombre_categoria" name="nombre_categoria" class="form-control" placeholder="Nombre de Categoria"
                                       value="">
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
                                

                                <textarea class="form-control resize_vertical" id="descripcion_categoria" name="descripcion_categoria" placeholder="Descripcion categoria" rows="5"></textarea>
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


<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Sub Categorias de Categoria {{$categoria->nombre_categoria}}
                    </h4>
                   <!-- <div class="pull-right">
                    <a href="{{ route('admin.categorias.create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> Crear categoria</a>-->
                    </div>
                </div>
                <br />
                <div class="panel-body">
                    @if ($categorias->count() >= 1)
                        <div class="table-responsive">

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Nombre</th>
                                    <th>Descripcion</th>
                                    <th>Creado</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($categorias as $row)
                                <tr>
                                    <td>{!! $row->id !!}</td>
                                    <td>{!! $row->nombre_categoria!!}</td>
                                    <td>{!! $row->descripcion_categoria !!}</td>
                                    <td>{!! $row->created_at->diffForHumans() !!}</td>
                                    <td>
                                            
                                            <a href="{{ route('admin.categorias.detalle', $row->id) }}">
                                                <i class="livicon" data-name="plus" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="Detalle"></i>
                                            </a>

                                            <a href="{{ route('admin.categorias.editson', $row->id) }}">
                                                <i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="editar categoria"></i>
                                            </a>
                                            <!-- let's not delete 'Admin' group by accident -->
                                            
                                            <a href="{{ route('admin.categorias.confirm-delete', $row->id) }}" data-toggle="modal" data-target="#delete_confirm">
                                            <i class="livicon" data-name="remove-alt" data-size="18"
                                                data-loop="true" data-c="#f56954" data-hc="#f56954"
                                                title="Eliminar"></i>
                                             </a>

                                             
                                              

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    @else
                        No se encontraron registros
                    @endif   
                </div>
            </div>
        </div>
    </div>    <!-- row-->
</section>
@stop
