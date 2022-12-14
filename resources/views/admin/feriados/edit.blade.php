@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar Feriado
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Editar Feriado
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Feriado</li>
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
                       Editar Feriado
                    </h4>
                </div>
                <div class="panel-body">
                    
                        {!! Form::model($feriados, ['url' => secure_url('admin/feriados/'. $feriados->id), 'method' => 'put', 'class' => 'form-horizontal']) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}
                          
                             <div class="form-group {{ $errors->
                            first('feriado', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Feriado
                            </label>
                            <div class="col-sm-5">
                                <input type="date" id="feriado" name="feriado" class="form-control" placeholder="Nombre de Transportistas"
                                       value="{!! old('feriado', $feriados->feriado) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('feriado', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                     
                       <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                
                                <a class="btn btn-danger" href="{{ secure_url('admin/feriados') }}">
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
