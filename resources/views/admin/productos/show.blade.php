@extends('admin/layouts/default')

@section('title')
Productos
@parent
@stop

@section('content')
<section class="content-header">
    <h1>Detalle de Producto</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                Escritorio
            </a>
        </li>
        <li>Productos</li>
        <li class="active">Detalle de Producto</li>
    </ol>
</section>

<section class="content paddingleft_right15">
    <div class="row">
       <div class="panel panel-primary">
        <div class="panel-heading clearfix">
            <h4 class="panel-title"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                Detalles de Productos
            </h4>
        </div>
            <div class="panel-body">


                    <div class="form-group">
                        {!! Form::label('id', 'Id:') !!}
                        <p>{!! $producto->id !!}</p>
                        <hr>
                    </div>

                    <!-- Undefined Field -->
                    <div class="form-group">
                        {!! Form::label('undefined', 'Nombre:') !!}
                        <p>{!! $producto->nombre_producto !!}</p>
                        <hr>
                    </div>

                    <!-- Name Producto Field -->
                    <div class="form-group">
                        {!! Form::label('name_producto', 'referencia:') !!}
                        <p>{!! $producto->referencia_producto !!}</p>
                        <hr>
                    </div>

                    <div class="form-group">
                        {!! Form::label('name_producto', 'Referencia Sap:') !!}
                        <p>{!! $producto->referencia_producto_sap !!}</p>
                        <hr>
                    </div>

                      <div class="form-group">
                        {!! Form::label('name_producto', 'Descripcion Corta:') !!}
                        <p>{!! $producto->descripcion_corta !!}</p>
                        <hr>
                    </div>

                     <div class="form-group">
                        {!! Form::label('name_producto', 'Descripcion Larga:') !!}
                        <p>{!! $producto->descripcion_larga !!}</p>
                        <hr>
                    </div>

                    <div class="form-group">
                        {!! Form::label('name_producto', 'Imagen Producto:') !!}
                        <p>{!! $producto->imagen_producto !!}</p>
                        <hr>
                    </div>

                    <div class="form-group">
                        {!! Form::label('name_producto', 'Seo Titulo:') !!}
                        <p>{!! $producto->seo_titulo !!}</p>
                        <hr>
                    </div>

                    <div class="form-group">
                        {!! Form::label('name_producto', 'Seo Descripcion:') !!}
                        <p>{!! $producto->seo_descripcion !!}</p>
                        <hr>
                    </div>

                    <div class="form-group">
                        {!! Form::label('name_producto', 'Seo Url:') !!}
                        <p>{!! $producto->slug !!}</p>
                        <hr>
                    </div>

                    <div class="form-group">
                        {!! Form::label('name_producto', 'Id Categoria:') !!}
                        <p>{!! $producto->id_categoria_default !!}</p>
                        <hr>
                    </div>

                      <div class="form-group">
                        {!! Form::label('name_producto', 'Id Marca:') !!}
                        <p>{!! $producto->id_marca !!}</p>
                        <hr>
                    </div>



            </div>
        </div>
    <div class="form-group">
           <a href="{!! route('admin.productos.index') !!}" class="btn btn-default">Back</a>
    </div>
  </div>
</section>
@stop
