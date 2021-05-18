@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
    Sliders
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
       Sliders
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Sliders</li>
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
                       Crear Sliders
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
                    {!! Form::open(['url' => secure_url('admin\sliders'), 'class' => 'form-horizontal', 'id' => 'sliderForm', 'name' => 'sliderForm', 'files'=> true]) !!}
                        <!-- CSRF Token -->

                        {{ csrf_field() }}

                        <div class="form-group {{ $errors->
                            first('nombre_slider', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Nombre 
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="nombre_slider" name="nombre_slider" class="form-control" placeholder="Nombre de Sliders"
                                       value="{!! old('nombre_slider') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('nombre_slider', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('descripcion_slider', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Descripcion 
                            </label>
                            <div class="col-sm-5">
                                

                                <textarea class="form-control resize_vertical" id="descripcion_slider" name="descripcion_slider" placeholder="Descripcion Sliders" rows="5">{!! old('descripcion_slider') !!}</textarea>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('descripcion_slider', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>





                         <div class="form-group clearfix">

                            <label for="title" class="col-md-2 control-label">Imagen </label>


                            <div class="col-md-5">

                            <div class="fileinput fileinput-new" data-provides="fileinput">

                                <div class="fileinput-new thumbnail" style="max-width: 200px; max-height: 200px;">

                                    <img src="{{ secure_asset('assets/images/authors/no_avatar.jpg') }}" alt="..."class="img-responsive"/>

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


                       <div class="form-group clearfix">

                            <label for="title" class="col-md-2 control-label">Imagen Mobile </label>


                            <div class="col-md-5">

                            <div class="fileinput fileinput-new" data-provides="fileinput">

                                <div class="fileinput-new thumbnail" style="max-width: 200px; max-height: 200px;">

                                    <img src="{{ secure_asset('assets/images/authors/no_avatar.jpg') }}" alt="..."class="img-responsive"/>

                                </div>

                                <div class="fileinput-preview fileinput-exists thumbnail"
                                     style="max-width: 200px; max-height: 150px;">
                                         
                                </div>

                                <div>
                                    <span class="btn btn-primary btn-file">

                                        <span class="fileinput-new">Seleccione Imagen </span>

                                        <span class="fileinput-exists">Cambiar</span>

                                        <input type="file" name="image_mobile" id="pic" accept="image/*"/>

                                    </span>
                                   
                                    <span class="btn btn-primary fileinput-exists"
                                          data-dismiss="fileinput">Eliminar</span>

                                </div>

                            </div>
                            </div>

                        </div>




                        <div class="form-group {{ $errors->first('link_slider', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                               Enlace para el Slider
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="link_slider" name="link_slider" class="form-control" placeholder="Enlace"
                                       value="{!! old('link_slider') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('link_slider', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                       

                        <div class="form-group {{ $errors->
                            first('order', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Posición del Slider
                            </label>
                            <div class="col-sm-5">
                                <input type="number" min="0" step="1" id="order" name="order" class="form-control" placeholder="Posición del Slider"
                                       value="{!! old('order') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('order', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                         <fieldset >

                         <div class="col-sm-10 col-sm-offset-2">
                
                                    <h3>Seleccione los almacenes.</h3>


                                        @foreach($almacenes as $a)

                                        <div class="checkbox">
                                          <label>
                                            <input type="checkbox" id="almacen_{{$a->id}}" name="almacenes_{{$a->id}}" value="{{$a->id}}"  >
                                           {{$a->nombre_almacen}}
                                          </label>
                                        </div>


                                        @endforeach

                                       

                                    </fieldset>






                        


                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                
                                <a class="btn btn-danger" href="{{ route('admin.sliders.index') }}">
                                    Cancelar
                                </a>

                                <button type="submit" class="btn btn-success">
                                    Crear
                                </button>
                            </div>
                        </div>
                    {!! Form::close() !!}
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
