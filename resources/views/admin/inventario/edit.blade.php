@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Agregar Inventario
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Agregar Inventario
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Agregar Inventario</li>

        <li class="active">Agregar Inventario</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Agregar Inventario
                    </h4>
                </div>
                <div class="panel-body">
                    
                        {!! Form::model($producto, ['url' => secure_url('admin/inventario/'. $producto->id), 'method' => 'put', 'class' => 'form-horizontal']) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}


                            <div class="form-group required">
                                <label for="operacion" class="col-sm-2 control-label"></label>
                                <div class="col-sm-5">
                                    <h3>{{ $producto->nombre_producto}}</h3>
                                 <p><b>EAN:</b> {{ $producto->referencia_producto }}</p>
                                 <p><b>SKU:</b> {{ $producto->referencia_producto_sap }}</p>

                                 @foreach($almacenes as  $a)

                                    @if(isset($inventario[$producto->id][$a->id_almacen]))

                                        <p><b> {{ 'Disponible '.$a->nombre_almacen.': '.$inventario[$producto->id][$a->id_almacen] }}</b></p>

                                    @else

                                        <p><b> {{ 'Disponible '.$a->nombre_almacen.': 0'}}</b></p>

                                    @endif



                                 @endforeach



                                </div>
                               
                               
                            </div>


                            <div class="form-group required">
                                <label for="operacion" class="col-sm-2 control-label">Operacion</label>
                                <div class="col-sm-5">
                                    <select class="form-control required" title="Selecciona Genero" name="operacion" id="operacion">
                                        <option value="">Seleccionar</option>
                                        <option value="1">Agregar</option>
                                        <option value="2">Descontar</option>
                                    </select>
                                    
                                </div>
                                <div class="col-sm-5">
                                    
                                </div>
                                <span class="help-block">{{ $errors->first('operacion', ':message') }}</span>
                            </div>


                            <div class="form-group required">
                                <label for="id_almacen" class="col-sm-2 control-label">Almacen</label>
                                <div class="col-sm-5">
                                    <select class="form-control required" title="Selecciona Almacen" name="id_almacen" id="id_almacen">
                                        <option value="">Seleccionar</option>

                                        @foreach($almacenes as $a)

                                            <option value="{{$a->id_almacen}}">{{$a->nombre_almacen}}</option>

                                        @endforeach

                                    </select>
                                    
                                </div>
                                <div class="col-sm-5">
                                    
                                </div>
                                <span class="help-block">{{ $errors->first('id_almacen', ':message') }}</span>
                            </div>


                          
                            <div class="form-group {{ $errors->
                            first('cantidad', 'has-error') }}">
                                <label for="title" class="col-sm-2 control-label">
                                    Cantidad
                                </label>
                                <div class="col-sm-5">
                                    <input required="true" type="number" step="1" min="0" id="cantidad" name="cantidad" class="form-control" placeholder="Cantidad"
                                           value="{!! old('cantidad') !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('cantidad', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>


                        <div class="form-group {{ $errors->
                            first('notas', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Descripcion 
                            </label>
                            <div class="col-sm-5">
                                

                                <textarea class="form-control resize_vertical" id="notas" name="notas" placeholder="Notas" rows="5">{!! old('notas') !!}</textarea>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('notas', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                       <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                
                                <a class="btn btn-danger" href="{{ route('admin.inventario.index') }}">
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-success">
                                    Agregar
                                </button>
                            </div>
                        </div>
                    </form>
                   
                </div>
            </div>
        </div>
    </div>
    <!-- row-->




    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Historico Movimientos
                    </h4>
                </div>
                <div class="panel-body">

                    @if (count($m) >= 1)
                        <div class="table-responsive">

                        <table class="table table-bordered" id="tbInventario">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Nombre</th>
                                    <th>EAN</th>
                                    <th>SKU</th>
                                    <th>Operacion</th>
                                    <th>Cantidad</th>
                                    <th>Almacen</th>
                                    <th>Usuario</th>
                                    <th>Orden</th>
                                    <th>Creado</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($m as $movimiento)

                                <tr>
                                    <td>{{$movimiento->id}}</td>
                                    <td>{{$movimiento->nombre_producto}}</td>
                                    <td>{{$movimiento->referencia_producto}}</td>
                                    <td>{{$movimiento->referencia_producto_sap}}</td>
                                    <td>
                                        @if($movimiento->operacion==1)

                                            {{'AGREGAR'}}

                                        @else
                                            
                                            {{'DESCONTAR'}}

                                        @endif

                                    </td>
                                    <td>{{$movimiento->cantidad}}</td>
                                    <td>{{$movimiento->nombre_almacen}}</td>
                                    <td>{{$movimiento->first_name.' '.$movimiento->last_name}}</td>

                                    @if(isset($movimiento->orden))

                                    <td><a target="_blank" href="{{secure_url('admin/ordenes/'.$movimiento->orden->id_orden.'/detalle')}}">{{$movimiento->orden->id_orden}}</a></td>

                                    @else

                                    <td>No Aplica</td>


                                    @endif


                                    <td>{{$movimiento->created_at->diffForHumans()}}</td>
                                </tr>

                                @endforeach


                            </tbody>
                        </table>
                        </div>
                    @else
                        No se encontraron registros
                    @endif   
                    
                   
                </div>
            </div>
        </div>
    </div>
    <!-- row-->
</section>

@stop
