@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar Forma de Envio
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Editar Forma de Envio
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Forma de Envios</li>
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
                       Editar Forma de Envio
                    </h4>
                </div>
                <div class="panel-body">
                    
                        {!! Form::model($forma, ['url' => secure_url('admin/formasenvio/'. $forma->id), 'method' => 'put', 'class' => 'form-horizontal']) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}

                            <div class="form-group {{ $errors->
                            first('sku', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Sku Forma de envios
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="sku" name="sku" class="form-control" placeholder="Sku de Forma de Envio"
                                       value="{!! old('sku', $forma->sku) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('nombre_forma_envios', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('email', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Email de Notificación
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="email" name="email" class="form-control" placeholder="Nombre de Forma de Envio"
                                       value="{!! old('email', $forma->email) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('email', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>



                          
                             <div class="form-group {{ $errors->
                            first('nombre_forma_envios', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Nombre Forma de Envio
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="nombre_forma_envios" name="nombre_forma_envios" class="form-control" placeholder="Nombre de Forma de Envio"
                                       value="{!! old('nombre_forma_envios', $forma->nombre_forma_envios) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('nombre_forma_envios', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('descripcion_forma_envios', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Descripción Forma de Envio
                            </label>
                            <div class="col-sm-5">
                                

                                <textarea class="form-control resize_vertical" id="descripcion_forma_envios" name="descripcion_forma_envios" placeholder="Descripcion Forma de Envio" rows="5">{!! old('descripcion_forma_envios', $forma->descripcion_forma_envios) !!}</textarea>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('descripcion_forma_envios', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                         <div class="form-group  {{ $errors->first('tipo', 'has-error') }}">
                                <label for="select21" class="col-sm-2 control-label">
                                    Tipo de envio
                                </label>
                                <div class="col-sm-5">   
                                 <select id="tipo" name="tipo" class="form-control ">
                                    <option value="">Seleccione</option>
                                        
                                       
                                        <option value="{{ 1 }}"
                                               @if($forma->tipo==1) {{'Selected'}} @endif >Fecha Variable</option>

                                        <option value="{{ 0}}"  @if($forma->tipo==0) {{'Selected'}} @endif
                                                 >Fecha Fija</option>
                                       
                                </select>
                                <div class="col-sm-4">
                                    {!! $errors->first('tipo', '<span class="help-block">:message</span> ') !!}
                                </div>
                                  
                                </div>
                               
                            </div>



                       <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                <a class="btn btn-danger" href="{{ route('admin.formasenvio.index') }}">
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
