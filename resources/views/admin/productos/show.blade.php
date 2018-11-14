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
                Detalles del producto
            </h4>
        </div>
            <div class="panel-body">


                     <div class="form-group">
                        {!! Form::label('name_producto', 'Imagen Producto: ') !!}
                        <p><img width="60px" src="{!! url('/').'/uploads/productos/'.$producto->imagen_producto !!}"></p>
                    </div>

                    <div class="form-group">
                        <p><b>Id:  </b><b></b>{!! $producto->id !!}</p>
                    </div>

                    <!-- Undefined Field -->
                    <div class="form-group">
                        <p><b>Nombre:  </b>{!! $producto->nombre_producto !!}</p>
                        
                    </div>

                    <!-- Name Producto Field -->
                    <div class="form-group">
                        <p><b>Referencia:  </b>{!! $producto->referencia_producto !!}</p>
                        
                    </div>

                    <div class="form-group">
                        <p><b>Referencia Sap: </b>{!! $producto->referencia_producto_sap !!}</p>
                        
                    </div>

                      <div class="form-group">
                        <p><b>Descripcion Corta:  </b>{!! $producto->descripcion_corta !!}</p>
                        
                    </div>

                     <div class="form-group">
                        <p><b>Descripcion Larga: </b>{!! $producto->descripcion_larga !!}</p>
                        
                    </div>

                   

                    <div class="form-group">
                        <p><b>Seo Titulo: </b>{!! $producto->seo_titulo !!}</p>
                        
                    </div>

                    <div class="form-group">
                        <p><b>Seo Descripcion: </b>{!! $producto->seo_descripcion !!}</p>
                        
                    </div>

                    <div class="form-group">
                        <p><b>Slug: </b>{!! $producto->slug !!}</p>
                        
                    </div>

                    

                    <div class="form-group">
                        <p><b>Categoria Defecto: </b>{!! $producto->nombre_categoria !!}</p>
                        
                    </div>

                      <div class="form-group">
                        <p><b>Marca: </b>{!! $producto->nombre_marca !!}</p>
                        
                    </div>

                      <div class="form-group">
                        <p><b>Precio Base: </b>{!! $producto->precio_base !!}</p>
                        
                    </div>

                    

                    




            </div>
        </div>
    <div class="form-group">
           <a href="{!! route('admin.productos.index') !!}" class="btn btn-default">Back</a>
    </div>
  </div>




<div class="row">
       <div class="panel panel-primary">
        <div class="panel-heading clearfix">
            <h4 class="panel-title"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                Precios del producto
            </h4>
        </div>
            <div class="panel-body">


                @if(isset($precio_grupo))


                <div class="table-responsive">
                    

                    <table class="table table-responsive">
                        <thead>
                            
                            <tr>
                                
                                <th> Detalles</th>
                                <th> Precio</th>
                                <th> Pum</th>
                            </tr>

                        </thead>
                        <tbody>
                            
                            @foreach($precio_grupo as $pg)

                            <tr>
                                
                                <td>{{ 'Precio para el '.$pg->role_name.' '.$pg->city_name }}</td>
                                <td>{{ $pg->precio_seleccion }}</td>
                                <td>{{ $pg->pum }}</td>
                            </tr>

                            @endforeach    

                        </tbody>
                        


                    </table>



                </div>

                @endif
                    



            </div>
        </div>
    <div class="form-group">
           <a href="{!! route('admin.productos.index') !!}" class="btn btn-default">Back</a>
    </div>
  </div>




<div class="row">
       <div class="panel panel-primary">
        <div class="panel-heading clearfix">
            <h4 class="panel-title"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                Categorias del Producto
            </h4>
        </div>
            <div class="panel-body">


                @if(isset($precio_grupo))


                <div class="table-responsive">
                    

                   <ul>
                        @if(isset($categorias))

                            @foreach($categorias as $categoria)

                            <li>{{ $categoria->nombre_categoria }}</li>
                            @endforeach

                        @endif
                       
                   </ul>



                </div>

                @endif
                    



            </div>
        </div>
    <div class="form-group">
           <a href="{!! route('admin.productos.index') !!}" class="btn btn-default">Back</a>
    </div>
  </div>





</section>
@stop
