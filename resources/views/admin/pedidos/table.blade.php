           <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
            <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}" />

<table class="table table-responsive table-striped table-bordered" id="alpProductos-table" width="100%">
    <thead>
     <tr>
        <th>Imagen</th>
        <th>Nombre de Producto</th>
        <th>Precio</th>
        <th>Accion</th>
     </tr>
    </thead>
    <tbody>

        @foreach($productos as $p)

        <tr>
            <td><img style="width: 60px;" src="{{secure_url('uploads/productos/60/'.$p->imagen_producto)}}" alt="{{$p->nombre_producto}}"></td>
            <td>
                <p><b>{{$p->nombre_producto}}</b></p>
                <p style="font-size: 0.8em; line-height: 1;">Presentacion : {{$p->presentacion_producto}}</p>
                <p style="font-size: 0.8em; line-height: 1;">Referencia: {{$p->referencia_producto}}</p>
                <p style="font-size: 0.8em; line-height: 1;">SKU: {{$p->referencia_producto_sap}}</p>
                <p style="font-size: 0.8em; line-height: 1;">Categoria: {{$p->nombre_categoria}}</p>
            </td>
            
            <td>{{$p->precio_base}}</td>
            <td><button class="btn btn-primary addproducto" 
                data-id="{{$p->id}}"
                >Agregar</button>
            </td>
         </tr>

        @endforeach
  
    </tbody>
</table>
