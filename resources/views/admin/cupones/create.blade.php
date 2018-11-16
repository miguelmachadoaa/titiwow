@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
    Cupones
    @parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Cupones
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Cupones</li>
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
                       Crear Cupon
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
                    <form class="form-horizontal" role="form" method="post" action="{{ route('admin.cupones.store') }}">
                        <!-- CSRF Token -->

                        {{ csrf_field() }}

                        <div class="form-group {{ $errors->
                            first('codigo_cupon', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Codigo Cupon
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="codigo_cupon" name="codigo_cupon" class="form-control" placeholder="Codigo Cupon"
                                       value="{!! old('codigo_cupon') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('codigo_cupon', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('valor_cupon', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Valor Cupon
                            </label>
                            <div class="col-sm-5">
                                <input type="number" step="0.01" min="0" id="valor_cupon" name="valor_cupon" class="form-control" placeholder="Valor Cupon"
                                       value="{!! old('valor_cupon') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('valor_cupon', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        <div class="form-group {{ $errors->first('tipo_reduccion', 'has-error') }}">
                            <label for="select21" class="col-sm-2 control-label">
                              Tipo de Cupon
                            </label>
                            <div class="col-sm-5">   
                             <select id="tipo_reduccion" name="tipo_reduccion" class="form-control ">
                                
                                <option value="">Seleccione</option>
                                <option value="1" >Absoluto</option>
                                <option value="2" >Porcetual</option>

                            </select>

                              {!! $errors->first('tipo_reduccion', '<span class="help-block">:message</span> ') !!}
                            </div>
                           
                        </div>



                        <div class="form-group {{ $errors->
                            first('limite_uso', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Limite de Uso
                            </label>
                            <div class="col-sm-5">
                                <input type="number" step="0.01" min="0" id="limite_uso" name="limite_uso" class="form-control" placeholder="Limite de Uso"
                                       value="{!! old('limite_uso') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('limite_uso', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>




                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                <a class="btn btn-danger" href="{{ route('admin.cupones.index') }}">
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
