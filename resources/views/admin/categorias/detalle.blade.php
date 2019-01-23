@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Categoria {{$categoria->id}}
@parent
@stop


{{-- page level styles --}}
@section('header_styles')

    <link href="{{ secure_asset('assets/vendors/summernote/summernote.css') }}" rel="stylesheet" />
    <link href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ secure_asset('assets/vendors/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    <link href="{{ secure_asset('assets/css/pages/blog.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}">
    <!--end of page level css-->
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Ver Categoria {{$categoria->id}}
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
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
                    
                        <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post" action="{{ secure_url('admin/categorias/'.$categoria->id.'/storeson') }}">
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
                                Descripción Categoria
                            </label>
                            <div class="col-sm-5">
                                

                                <textarea class="form-control resize_vertical" id="descripcion_categoria" name="descripcion_categoria" placeholder="Descripcion categoria" rows="5"></textarea>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('descripcion_categoria', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>







                       

                        <div class="form-group">

                            <label for="title" class="col-sm-2 control-label">Imagen de Categoria</label>


                            <div class="col-sm-5">

                            <div class="fileinput fileinput-new" data-provides="fileinput">

                                <div class="fileinput-new thumbnail" style="max-width: 200px; max-height: 200px;">

                                    <img src="{{ secure_asset('assets/images/authors/no_avatar.jpg') }}" alt="..."class="img-responsive"/>

                                </div>

                                <div class="fileinput-preview fileinput-exists thumbnail"
                                     style="max-width: 200px; max-height: 150px;">
                                         
                                </div>

                                <div>
                                    <span class="btn btn-primary btn-file">

                                        <span class="fileinput-new">Select image</span>

                                        <span class="fileinput-exists">Change</span>

                                        <input type="file" name="image" id="pic" accept="image/*"/>

                                    </span>
                                   
                                    <span class="btn btn-primary fileinput-exists"
                                          data-dismiss="fileinput">Remove</span>

                                </div>

                            </div>
                            </div>

                        </div>

                        <div class="form-group {{ $errors->first('slug', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Slug Categoria
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="slug" name="slug" class="form-control" placeholder="Slug Categoria"
                                       value="{!! old('slug') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('slug', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>
                        <div class="form-group {{ $errors->
                            first('seo_titulo', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Seo Titulo
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="seo_titulo" name="seo_titulo" class="form-control" placeholder="Seo Titulo"
                                       value="{!! old('seo_titulo') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('seo_titulo', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>
                        <div class="form-group {{ $errors->
                            first('seo_descripcion', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                            Seo Descripción
                            </label>
                            <div class="col-sm-5">
                                <input type="text"  id="seo_descripcion" name="seo_descripcion" class="form-control" placeholder="Seo Descripcion"
                                       value="{!! old('seo_descripcion') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('seo_descripcion', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>
                       <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                <a class="btn btn-danger" href="{{ secure_url('admin/categorias') }}">
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-success">Crear</button>
                                
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
                                            
                                            <a href="{{ secure_url('admin/categorias/'.$row->id.'/detalle') }}">
                                                <i class="livicon" data-name="plus" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="Detalle"></i>
                                            </a>

                                            <a href="{{ secure_url('admin/categorias/'.$row->id.'/editson') }}">
                                                <i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="editar categoria"></i>
                                            </a>
                                            <!-- let's not delete 'Admin' group by accident -->
                                            
                                            <a href="{{ secure_url('admin/categorias/'.$row->id.'/confirm-delete') }}" data-toggle="modal" data-target="#delete_confirm">
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


{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<!--edit blog-->
<script src="{{ secure_asset('assets/vendors/summernote/summernote.js') }}" type="text/javascript"></script>

<script src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>

<script src="{{ secure_asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>

<script type="text/javascript" src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>

<script src="{{ secure_asset('assets/js/pages/add_newblog.js') }}" type="text/javascript"></script>

@stop
