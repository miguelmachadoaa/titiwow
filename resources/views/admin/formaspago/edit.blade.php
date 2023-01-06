@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar Forma de Pago
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Editar Forma de Pago
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Forma de Pagos</li>
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
                       Editar Forma de Pago
                    </h4>
                </div>
                <div class="panel-body">
                    
                        {!! Form::model($forma, ['url' => secure_url('admin/formaspago/'. $forma->id), 'method' => 'put', 'class' => 'form-horizontal']) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}
                          
                        <div class="form-group {{ $errors->
                            first('nombre_forma_pago', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Nombre Forma de Pago
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="nombre_forma_pago" name="nombre_forma_pago" class="form-control" placeholder="Nombre de Forma de Pago"
                                       value="{!! old('nombre_forma_pago', $forma->nombre_forma_pago) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('nombre_forma_pago', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('descripcion_forma_pago', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Descaripcion Forma de Pago
                            </label>
                            <div class="col-sm-5">
                                

                                <textarea class="form-control resize_vertical" id="descripcion_forma_pago" name="descripcion_forma_pago" placeholder="Descripcion Forma de Pago" rows="5">{!! old('descripcion_forma_pago', $forma->descripcion_forma_pago) !!}</textarea>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('descripcion_forma_pago', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        <div class="form-group {{ $errors->
                            first('orden', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Orden Forma de Pago
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="orden" name="orden" class="form-control" placeholder="Orden  de Forma de Pago"
                                       value="{!! old('orden', $forma->orden) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('orden', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                         <div class="form-group  {{ $errors->first('vuelto', 'has-error') }}">
                                <label for="select21" class="col-sm-2 control-label">
                                    Se usa para dar vuelto
                                </label>
                                <div class="col-sm-5">   
                                 <select id="vuelto" name="vuelto" class="form-control ">
                                    <option value="">Seleccione</option>
                                        
                                       
                                        <option value="{{ 0 }}"
                                                @if($forma->vuelto == 0) selected="selected" @endif >Desactivado</option>

                                        <option value="{{ 1 }}"
                                                @if($forma->vuelto == 1) selected="selected" @endif >Activado</option>
                                       
                                </select>
                                <div class="col-sm-4">
                                    {!! $errors->first('vuelto', '<span class="help-block">:message</span> ') !!}
                                </div>
                                  
                                </div>
                               
                            </div>



                       <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                <a class="btn btn-danger" href="{{ route('admin.formaspago.index') }}">
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
