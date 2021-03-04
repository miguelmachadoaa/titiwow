<table class="" id="categoriastable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre de Producto</th>
            <th>Presentación de Producto</th>
            <th>Referencia</th>
            <th>Ciudad</th>
            <th>Rol</th>
            <th>Tipo Descuento</th>
            <th>Precio</th>
            <th>Id Usuario</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($precios as $producto)
        <tr>
             <td>{{$producto->id}}</td>
            <td>{{$producto->nombre_producto}}</td>
            <td>{{$producto->presentacion_producto}}</td>
            <td>{{$producto->referencia_producto}}</td>

                <td>{{ $producto->city_name }}</td>
                <td>{{ $producto->name  }}</td>

            <td>

               @if($producto->operacion==1)

                   Precio Base
               @endif

               @if($producto->operacion==2)

                    Porcentual 
               @endif

               @if($producto->operacion==3)

                    Absoluto
               @endif

            </td>
              

            <td>

               @if($producto->operacion==1)

                    {{ $producto->precio_base}}
               @endif

               @if($producto->operacion==2)

                    {{ $producto->precio_base*(1-($producto->precio/100))}}
               @endif

               @if($producto->operacion==3)

                    {{ $producto->precio }}
               @endif

                </td>

                <td>
                    {{$producto->id_user}}
                </td>

            
          
        </tr>
        @endforeach
    </tbody>
</table>
                       