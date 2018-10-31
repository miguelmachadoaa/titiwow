@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar Marca
@parent
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
                    
                        {!! Form::model($marca, ['url' => URL::to('admin/marcas/'. $marca->id), 'method' => 'put', 'class' => 'form-horizontal', 'files'=> true]) !!}
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
                                Descaripcion Marca
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
