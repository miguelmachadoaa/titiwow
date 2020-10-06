           <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
            <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}" />

            <div class="row">
                
                <div class="col-sm-12 text-center">

                    @if(isset($almacen->nombre_almacen))
                
                        <h3>Usted se Encuentra en el Almacen {{$almacen->nombre_almacen}}</h3>

                        <h3>{{$descripcion}}</h3>

                    @else

                        <h3>Debe Seleccionar un Almacen </h3>


                    @endif

                </div>

            </div>

            

            @if(count($productos))

<table class="table table-responsive table-striped table-bordered" id="alpProductos-table" width="100%">
    <thead>
     <tr>
        <th>Imagen</th>
        <th>Nombre de Producto</th>
        <th>Precio</th>
        <th>Oferta</th>
        <th>Existencia</th>
        <th>Accion</th>
     </tr>
    </thead>
    <tbody>

        @foreach($productos as $p)

        @if(isset($cart['inventario'][$p->id]))

            @if($cart['inventario'][$p->id]>0)


            <tr>
                <td><img style="width: 60px;" src="{{secure_url('uploads/productos/60/'.$p->imagen_producto)}}" alt="{{$p->nombre_producto}}"></td>
                <td>
                    <p><b>{{$p->nombre_producto}}</b></p>
                    <p style="font-size: 0.8em; line-height: 1;">Presentacion : {{$p->presentacion_producto}}</p>
                    <p style="font-size: 0.8em; line-height: 1;">Referencia: {{$p->referencia_producto}}</p>
                    <p style="font-size: 0.8em; line-height: 1;">SKU: {{$p->referencia_producto_sap}}</p>
                    <p style="font-size: 0.8em; line-height: 1;">Categoria: {{$p->nombre_categoria}}</p>
                </td>
                
                <td>{{number_format($p->precio_base,0,',','.')}}</td>
                <td>{{number_format($p->precio_oferta,0,',','.')}}</td>
                <td>{{$cart['inventario'][$p->id]}}</td>
                <td><button class="btn btn-primary addproducto" 
                    data-id="{{$p->id}}"
                    >Agregar</button>
                </td>
             </tr>

             @endif
             
         @endif


        @endforeach
  
    </tbody>
</table>

@else

    <div class="alert alert-danger ">No hay productos disponibles</div>

@endif
