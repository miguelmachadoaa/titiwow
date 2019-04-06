@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar Empresa
@parent
@stop

@section('header_styles')

    <link href="{{ secure_asset('assets/vendors/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />

    <link href="{{ secure_asset('assets/css/pages/blog.css') }}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}">

@stop


{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Editar Empresa
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Empresas</li>
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
                       Editar Empresa
                    </h4>
                </div>
                <div class="panel-body">
                    
                        {!! Form::model($empresas, ['url' => secure_url('admin/empresas/'. $empresas->id), 'method' => 'put', 'class' => 'form-horizontal', 'files'=> true]) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}
                          
                        <div class="form-group {{ $errors->
                            first('nombre_empresa', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Nombre Empresa
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="nombre_empresa" name="nombre_empresa" class="form-control" placeholder="Nombre de Empresa"
                                       value="{!! old('nombre_empresa', $empresas->nombre_empresa) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('nombre_empresa', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('descripcion_empresa', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Descripción Empresa
                            </label>
                            <div class="col-sm-5">
                                

                                <textarea class="form-control resize_vertical" id="descripcion_empresa" name="descripcion_empresa" placeholder="Descripcion Empresa" rows="5">{!! old('descripcion_empresa', $empresas->descripcion_empresa) !!}</textarea>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('descripcion_empresa', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('descuento_empresa', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Descuento Empresa
                            </label>
                            <div class="col-sm-5">
                                <input type="number" min="0" step="0.01" max="100" id="descuento_empresa" name="descuento_empresa" class="form-control" placeholder="Nombre de Empresa"
                                       value="{!! old('descuento_empresa', $empresas->descuento_empresa) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('descuento_empresa', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        <div class="form-group col-sm-12  clearfix">

                            <label for="title" class="col-sm-3 col-xs-12 control-label">Imagen </label>


                            <div class="col-sm-9 col-xs-12">

                            <div class="fileinput fileinput-new" data-provides="fileinput">

                                <div class="fileinput-new thumbnail" style="max-width: 200px; max-height: 200px;">

                                    @if($empresas->imagen!='')

                                        <img src="{{URL::to('uploads/sliders/'.$empresas->imagen)}}" class="img-responsive" alt="Image">

                                    @else
                                        
                                        <img src="{{ secure_asset('assets/images/authors/no_avatar.jpg') }}" alt="..."
                                             class="img-responsive"/>

                                    @endif

                                </div>

                                <div class="fileinput-preview fileinput-exists thumbnail"
                                     style="max-width: 200px; max-height: 150px;">
                                         
                                </div>

                                <div>
                                    <span class="btn btn-primary btn-file">

                                        <span class="fileinput-new">Seleccione Imagen </span>

                                        <span class="fileinput-exists">Cambiar</span>

                                        <input type="file" name="image" id="pic" accept="image/*"/>

                                    </span>
                                   
                                    <span class="btn btn-primary fileinput-exists"
                                          data-dismiss="fileinput">Eliminar</span>

                                </div>

                            </div>
                            </div>

                        </div>









                       <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                
                                <a class="btn btn-danger" href="{{ route('admin.empresas.index') }}">
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
@section('footer_scripts')


<script src="{{ secure_asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>

<script type="text/javascript" src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>

@stop
