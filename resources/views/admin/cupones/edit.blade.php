@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar Cupon
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Editar Cupón
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Cupones</li>
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
                       Editar Cupón
                    </h4>
                </div>
                <div class="panel-body">
                    
                        {!! Form::model($cupon, ['url' => secure_url('admin/cupones/'. $cupon->id), 'method' => 'put', 'class' => 'form-horizontal']) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}
                          
                             <div class="form-group {{ $errors->
                            first('codigo_cupon', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Código Cupón
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="codigo_cupon" name="codigo_cupon" class="form-control" placeholder="Codigo Cupón"
                                       value="{!! old('codigo_cupon', $cupon->codigo_cupon) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('codigo_cupon', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->first('tipo_reduccion', 'has-error') }}">
                            <label for="select21" class="col-sm-2 control-label">
                              Tipo de Cupón
                            </label>
                            <div class="col-sm-5">   
                             <select id="tipo_reduccion" name="tipo_reduccion" class="form-control ">
                                
                                <option value="">Seleccione</option>
                                <option value="1" @if($cupon->tipo_reduccion=='1') selected @endif>Absoluto</option>
                                <option value="2" @if($cupon->tipo_reduccion=='2') selected @endif>Porcetual</option>

                            </select>

                              {!! $errors->first('tipo_reduccion', '<span class="help-block">:message</span> ') !!}
                            </div>
                           
                        </div>

                        <div class="form-group {{ $errors->
                            first('valor_cupon', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Valor Cupón
                            </label>
                            <div class="col-sm-5">
                                <input type="number" step="0.01" min="0" id="valor_cupon" name="valor_cupon" class="form-control" placeholder="Valor Cupon"
                                       value="{!! old('valor_cupon', $cupon->valor_cupon) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('valor_cupon', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        



                        <div class="form-group {{ $errors->
                            first('limite_uso', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Limite de Uso
                            </label>
                            <div class="col-sm-5">
                                <input type="number" step="1" min="0" id="limite_uso" name="limite_uso" class="form-control" placeholder="Limite de Uso"
                                       value="{!! old('limite_uso', $cupon->limite_uso) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('limite_uso', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('limite_uso_persona', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Limite de Uso por persona
                            </label>
                            <div class="col-sm-5">
                                <input type="number" step="1" min="1" id="limite_uso_persona" name="limite_uso_persona" class="form-control" placeholder="Limite de Uso"
                                       value="{!! old('limite_uso_persona', $cupon->limite_uso_persona) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('limite_uso_persona', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                         <div class="form-group {{ $errors->
                            first('fecha_inicio', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Fecha Inicio
                            </label>
                            <div class="col-sm-5">
                                <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" placeholder="Codigo Cupon"
                                       value="{!! old('fecha_inicio', $cupon->fecha_inicio) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('fecha_inicio', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                         <div class="form-group {{ $errors->
                            first('fecha_final', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Fecha Final 
                            </label>
                            <div class="col-sm-5">
                                <input type="date" id="fecha_final" name="fecha_final" class="form-control" placeholder="Codigo Cupon"
                                       value="{!! old('fecha_final', $cupon->fecha_final) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('fecha_final', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                         <div class="form-group {{ $errors->
                            first('monto_minimo', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Monto Minimo en el carro
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="monto_minimo" name="monto_minimo" class="form-control" placeholder="Monto Minimo en el Carro"
                                       value="{!! old('monto_minimo', $cupon->monto_minimo) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('monto_minimo', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('maximo_productos', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Maxima cantidad por productos
                            </label>
                            <div class="col-sm-5">
                                <input type="number"  min="0" step="1" id="maximo_productos" name="maximo_productos" class="form-control" placeholder="Maximo cantidad por productos"
                                       value="{!! old('maximo_productos', $cupon->maximo_productos) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('maximo_productos', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                       <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                
                                <a class="btn btn-danger" href="{{ secure_url('admin/cupones') }}">
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
