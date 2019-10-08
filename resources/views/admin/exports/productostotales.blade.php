<table class="" id="categoriastable">
    <thead>
        <tr>
            <th ><b>Id_Detalle</b></th>
            <th ><b>Id_Orden</b></th>
            <th ><b>id_producto</b></th>
            <th ><b>Categoria</b></th>
            <th ><b>Marca</b></th>
            <th><b>Producto</b></th>
            <th><b>Presentación</b></th>
            <th><b>Cantidad de unidades</b></th>
            <th><b>Monto venta</b></th>
            <th><b>Base Impuesto</b></th>
            <th><b>Monto Impuesto</b></th>
            <th><b>Numero de Pedidos</b></th>
            <th><b>Fecha</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($productos as $row)
        <tr>
            <td>{!! $row->id !!}</td>
            <td>{!! $row->id_orden!!}</td>
            <td>{!! $row->id_producto!!}</td>
            <td>{!! $row->nombre_categoria!!}</td>
            <td>{!! $row->nombre_marca!!}</td>
            <td>{!! $row->nombre_producto !!}</td>
            <td>{!! $row->presentacion_producto !!}</td>
            <td>{!! $row->cantidad !!}</td>
            <td>{!! $row->precio_total !!}</td>
            <td>
                @if($row->valor_impuesto==0)
                    {{ 0 }}
                @else
                    {!! $row->monto_impuesto/$row->valor_impuesto !!}
                @endif

            </td>
            <td>{!! $row->monto_impuesto !!}</td>
            <td>{!! $row->num_pedidos !!}</td>
            <td>{!! $row->fecha !!}</td>
          
        </tr>
        @endforeach
    </tbody>
</table>
                       