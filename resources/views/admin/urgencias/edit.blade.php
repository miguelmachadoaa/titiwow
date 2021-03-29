@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar urgencia
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
        Editar urgencia
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>urgencias</li>
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
                       Editar urgencia
                    </h4>
                </div>
                <div class="panel-body">
                    
                        {!! Form::model($urgencia, ['url' => secure_url('admin/urgencias/'. $urgencia->id), 'method' => 'put', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data', 'files'=> true]) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}
                          
                             <div class="form-group {{ $errors->
                            first('nombre_urgencia', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Nombre urgencia
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="nombre_urgencia" name="nombre_urgencia" class="form-control" placeholder="Nombre de urgencia"
                                       value="{!! old('nombre_urgencia', $urgencia->nombre_urgencia) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('nombre_urgencia', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('descripcion_urgencia', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Descripción urgencia
                            </label>
                            <div class="col-sm-5">
                                

                                <textarea class="form-control resize_vertical" id="descripcion_urgencia" name="descripcion_urgencia" placeholder="Descripción urgencia" rows="5">{!! old('descripcion_urgencia', $urgencia->descripcion_urgencia) !!}</textarea>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('descripcion_urgencia', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                      


                       <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                <a class="btn btn-danger" href="{{ route('admin.urgencias.index') }}">
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
<script src="{{ secure_asset('assets/vendors/summernote/summernote.js') }}" type="text/javascript"></script>

<script src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>

<script src="{{ secure_asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>

<script type="text/javascript" src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>

<script src="{{ secure_asset('assets/js/pages/add_newblog.js') }}" type="text/javascript"></script>

@stop

