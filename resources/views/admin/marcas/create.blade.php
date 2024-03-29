@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
    Marca
    @parent
@stop

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
        Marca
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Marca</li>
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
                       Crear Marca
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
                    <form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="{{ secure_url('admin/marcas/create') }}">
                        <!-- CSRF Token -->

                        {{ csrf_field() }}

                        <div class="form-group {{ $errors->
                            first('nombre_marca', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Nombre 
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="nombre_marca" name="nombre_marca" class="form-control" placeholder="Nombre de Marca"
                                       value="{!! old('nombre_marca') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('nombre_marca', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('descripcion_marca', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Descripción 
                            </label>
                            <div class="col-sm-5">
                                

                                <textarea class="form-control resize_vertical" id="descripcion_marca" name="descripcion_marca" placeholder="Descripcion Marca" rows="5">{!! old('descripcion_marca') !!}</textarea>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('descripcion_marca', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>




                        <div class="form-group">

                            <label for="title" class="col-sm-2 control-label">Imagen de Marca</label>


                            <div class="col-sm-5">

                            <div class="fileinput fileinput-new" data-provides="fileinput">

                                <div class="fileinput-new thumbnail" style="max-width: 200px; max-height: 200px;">

                                    <img src="{{ secure_asset('/uploads/categorias/default.png') }}" alt="..."class="img-responsive"/>

                                </div>

                                <div class="fileinput-preview fileinput-exists thumbnail"
                                     style="max-width: 200px; max-height: 150px;">
                                         
                                </div>

                                <div>
                                    <span class="btn btn-primary btn-file">

                                        <span class="fileinput-new">Seleccinar Imagen</span>

                                        <span class="fileinput-exists">Cambiar</span>

                                        <input type="file" name="image" id="pic" accept="image/*"/>

                                    </span>
                                   
                                    <span class="btn btn-primary fileinput-exists"
                                          data-dismiss="fileinput">Eliminar</span>

                                </div>

                            </div>
                            </div>

                        </div>

                        <div class="form-group {{ $errors->first('slug', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Slug Marca
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="slug" name="slug" class="form-control" placeholder="Slug Marca"
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
                                <input type="text"  id="seo_descripcion" name="seo_descripcion" class="form-control" maxlength="160" placeholder="Seo Descripcion"
                                       value="{!! old('seo_descripcion') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('seo_descripcion', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>
                       

                        <div class="form-group {{ $errors->
                            first('order', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Posición de la Marca
                            </label>
                            <div class="col-sm-5">
                                <input type="number" min="0" step="1" id="order" name="order" class="form-control" placeholder="Posición de la Marca"
                                       value="{!! old('order') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('order', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>




                                            <fieldset>
                                                
                                                <div class="col-sm-10 col-sm-offset-2">
                            
                                                <h3>Opciones robots.</h3>

                                                <div class="checkbox">
                                                  <label>
                                                    <input type="checkbox" id="robots_all" name="robots_all" value="all" >
                                                   All
                                                  </label>
                                                </div>

                                                <div class="checkbox">
                                                      <label>
                                                        <input type="checkbox" id="robots_index" name="robots_index" value="index"   checked  >
                                                       Index
                                                      </label>
                                                    </div>

                                                    <div class="checkbox">
                                                      <label>
                                                        <input type="checkbox" id="robots_follow" name="robots_follow" value="follow"   checked  >
                                                       Follow
                                                      </label>
                                                    </div>




                                                <div class="checkbox">
                                                  <label>
                                                    <input type="checkbox" id="robots_noindex" name="robots_noindex" value="noindex">
                                                   noindex
                                                  </label>
                                                </div>


                                                <div class="checkbox">
                                                  <label>
                                                    <input type="checkbox" id="robots_nofollow" name="robots_nofollow" value="nofollow">
                                                   nofollow
                                                  </label>
                                                </div>


                                                <div class="checkbox">
                                                  <label>
                                                    <input type="checkbox" id="robots_none" name="robots_none" value="none">
                                                   none
                                                  </label>
                                                </div>

                                                <div class="checkbox">
                                                  <label>
                                                    <input type="checkbox" id="robots_noarchive" name="robots_noarchive" value="noarchive">
                                                   noarchive
                                                  </label>
                                                </div>


                                                <div class="checkbox">
                                                  <label>
                                                    <input type="checkbox" id="robots_nosnippet" name="robots_nosnippet" value="nosnippet">
                                                   nosnippet
                                                  </label>
                                                </div>


                                                <div class="checkbox">
                                                  <label>
                                                    <input type="checkbox" id="robots_notranslate" name="robots_notranslate" value="notranslate">
                                                   notranslate
                                                  </label>
                                                </div>


                                                <div class="checkbox">
                                                  <label>
                                                    <input type="checkbox" id="robots_noimageindex" name="robots_noimageindex" value="noimageindex">
                                                   noimageindex
                                                  </label>
                                                </div>
                                                </div>



                                                </fieldset>

                                          

                            <br>
                            <br>


                            

                        

                        <div class="form-group">
                            <label for="destacado" class="col-sm-2 control-label"> Destacado</label>
                            <div class="col-sm-10">
                                <input id="destacado" name="destacado" type="checkbox"
                                       class="pos-rel p-l-30 custom-checkbox "
                                       value="1" @if(old('destacado')) checked="checked" @endif >
                                <span>¿Destacado?</span></div>

                        </div>


                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                
                                <a class="btn btn-danger" href="{{ secure_url('admin/marcas') }}">
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

@section('footer_scripts')
<!-- begining of page level js -->
<!--edit blog-->
<script src="{{ secure_asset('assets/vendors/summernote/summernote.js') }}" type="text/javascript"></script>

<script src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>

<script src="{{ secure_asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>

<script type="text/javascript" src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>

<script src="{{ secure_asset('assets/js/pages/add_newblog.js') }}" type="text/javascript"></script>

@stop
