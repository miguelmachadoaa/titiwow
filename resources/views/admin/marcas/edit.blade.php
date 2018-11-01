@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar Marca
@parent
@stop


{{-- page level styles --}}
@section('header_styles')

    <link href="{{ asset('assets/vendors/summernote/summernote.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/pages/blog.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}">
    <!--end of page level css-->
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Editar Marca
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Marcas</li>
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
                       Editar Marca
                    </h4>
                </div>
                <div class="panel-body">
                    
                        {!! Form::model($marca, ['url' => URL::to('admin/marcas/'. $marca->id), 'method' => 'put', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data', 'files'=> true]) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}
                          
                             <div class="form-group {{ $errors->
                            first('nombre_marca', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Nombre Marca
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="nombre_marca" name="nombre_marca" class="form-control" placeholder="Nombre de Marca"
                                       value="{!! old('nombre_marca', $marca->nombre_marca) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('nombre_marca', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('descripcion_marca', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Descripcion Marca
                            </label>
                            <div class="col-sm-5">
                                

                                <textarea class="form-control resize_vertical" id="descripcion_marca" name="descripcion_marca" placeholder="Descripcion Marca" rows="5">{!! old('descripcion_marca', $marca->descripcion_marca) !!}</textarea>
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

                                
                                    @if($marca->imagen_marca!='0')

                                        <img src="{{URL::to('uploads/marcas/'.$marca->imagen_marca)}}" class="img-responsive" alt="Image">

                                    @else
                                        
                                        <img src="{{ asset('assets/images/authors/no_avatar.jpg') }}" alt="..."
                                            class="img-responsive"/>

                                    @endif

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
                                Slug 
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="slug" name="slug" class="form-control" placeholder="Slug Marca"
                                       value="{!! old('slug', $marca->slug) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('slug', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        <div class="form-group {{ $errors->
                            first('order', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Posicion 
                            </label>
                            <div class="col-sm-5">
                                <input type="number" step="1" min="0" id="order" name="order" class="form-control" placeholder="Posicion de la Marca"
                                       value="{!! old('order', $marca->order) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('order', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="destacado" class="col-sm-2 control-label"> Marketing</label>
                            <div class="col-sm-10">
                                <input id="destacado" name="destacado" type="checkbox"
                                       class="pos-rel p-l-30 custom-checkbox "
                                       value="1" @if(old('destacado', $marca->destacado) ) checked="checked" @endif >
                                <span>¿Destacado?</span></div>

                        </div>



                       <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                <a class="btn btn-danger" href="{{ route('admin.marcas.index') }}">
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


{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<!--edit blog-->
<script src="{{ asset('assets/vendors/summernote/summernote.js') }}" type="text/javascript"></script>

<script src="{{ asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>

<script src="{{ asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>

<script type="text/javascript" src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>

<script src="{{ asset('assets/js/pages/add_newblog.js') }}" type="text/javascript"></script>

@stop

