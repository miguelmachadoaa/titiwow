@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
    Feriados
    @parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
       Feriados
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Feriados</li>
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
                       Crear Feriado
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
                    <form class="form-horizontal" role="form" method="post" action="{{ secure_url('admin/feriados/create') }}">
                        <!-- CSRF Token -->

                        {{ csrf_field() }}

                        <div class="form-group {{ $errors->
                            first('feriado', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Dia Feriado 
                            </label>
                            <div class="col-sm-5">
                                <input type="date" id="feriado" name="feriado" class="form-control" placeholder="Nombre de transportistas"
                                       value="{!! old('feriado') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('feriado', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                       





                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                
                                <a class="btn btn-danger" href="{{ route('admin.transportistas.index') }}">
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
