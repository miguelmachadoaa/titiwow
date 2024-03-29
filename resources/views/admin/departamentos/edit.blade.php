@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar areas
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
        Editar areas
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>areas</li>
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
                       Editar areas
                    </h4>
                </div>
                <div class="panel-body">
                    
                        {!! Form::model($departamento, ['url' => secure_url('admin/departamentos/'. $departamento->id), 'method' => 'put', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data', 'files'=> true]) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}
                          
                             <div class="form-group {{ $errors->
                            first('nombre_departamento', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Nombre departamento
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="nombre_departamento" name="nombre_departamento" class="form-control" placeholder="Nombre de departamento"
                                       value="{!! old('nombre_departamento', $departamento->nombre_departamento) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('nombre_departamento', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('descripcion_departamento', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Descripción departamento
                            </label>
                            <div class="col-sm-5">
                                

                                <textarea class="form-control resize_vertical" id="descripcion_departamento" name="descripcion_departamento" placeholder="Descripción departamento" rows="5">{!! old('descripcion_departamento', $departamento->descripcion_departamento) !!}</textarea>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('descripcion_departamento', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        <div class="form-group {{ $errors->
                            first('correos', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                               Correos de Notificación <small>Separar con ;</small>
                            </label>
                            <div class="col-sm-5">
                                
                               <input style="margin: 4px 0;" id="correos" name="correos" type="text" placeholder="Correos de Envio" value="{!! old('correos'), $departamento->correos !!}" class="form-control">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('correos', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                       <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                <a class="btn btn-danger" href="{{ route('admin.departamentos.index') }}">
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

