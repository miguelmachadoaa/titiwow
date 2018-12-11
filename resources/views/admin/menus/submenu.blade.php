@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Sub Menu {{$detalle->id}}
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
        Ver Detalles del Sub Menu {{$detalle->id}}
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Detalles</li>
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
                       Agregar Sub Menu a  Menu: {{$detalle->name}}
                    </h4>
                </div>
                <div class="panel-body">
                    
                        <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post" action="{{ secure_url('admin/menus/'.$detalle->id.'/storesub') }}">
                            <!-- CSRF Token -->
                            {{ csrf_field() }}

                             <input type="hidden" name="parent" id="parent" value="{{ $detalle->id }}">
                          
                             <div class="form-group {{ $errors->
                            first('name', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Nombre Sub Menu
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="name" name="name" class="form-control" placeholder="Nombre de Sub Menu"
                                       value="">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('name', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('slug', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Slug
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="slug" name="slug" class="form-control" placeholder="Slug Sub Menu" value="">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('slug', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                       <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                
                                <a class="btn btn-danger" href="{{ secure_url('admin/menus') }}">
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
                       Sub Menus de Menu {{$detalle->name}}
                    </h4>
                   
                    </div>

                </div>

                <br />

                <div class="panel-body">

                    @if ($detalles->count()>= 1)

                        <div class="table-responsive">

                        <table class="table table-bordered">

                            <thead>

                                <tr>

                                    <th>Id</th>
                                    <th>Nombre</th>
                                    <th>Slug</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($detalles as $row)
                                <tr>
                                    <td>{!! $row->id !!}</td>
                                    <td>{!! $row->name!!}</td>
                                    <td>{!! $row->slug !!}</td>
                                    
                                    <td>
                                            
                                            <a href="{{ route('admin.menus.submenu', $row->id) }}">
                                                <i class="livicon" data-name="plus" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="Detalle"></i>
                                            </a>

                                            <a href="{{ route('admin.menus.editson', $row->id) }}">
                                                <i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="editar categoria "></i>
                                            </a>
                                            <!-- let's not delete 'Admin' group by accident -->
                                            
                                            <a href="{{ route('admin.menus.confirm-delete', $row->id) }}" data-toggle="modal" data-target="#delete_confirm">
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
