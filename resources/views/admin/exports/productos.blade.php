<table class="" id="categoriastable">
    <thead>
        <tr>
            <th ><b>Id_Detalle</b></th>
            <th ><b>Id_Orden</b></th>
            <th ><b>id_producto</b></th>
            <th ><b>Categoria</b></th>
            <th ><b>Marca</b></th>
            <th><b>Producto</b></th>
            <th><b>Precio</b></th>
            <th><b>Presentacion</b></th>
            <th><b>Cantidad de unidades</b></th>
            <th><b>Numero de Pedidos</b></th>
            <th><b>Fecha</b></th>
            <th><b>Barrio</b></th>
            <th><b>Nombre Cliente </b></th>
            <th><b>Email</b></th>
            <th><b>Almacen</b></th>
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
            <td>{!! $row->precio_unitario !!}</td>
            <td>{!! $row->presentacion_producto !!}</td>
            <td>{!! $row->cantidad !!}</td>
            <td>{!! $row->num_pedidos !!}</td>
            <td>{!! $row->fecha !!}</td>
            <td>{!! $row->barrio_address !!}</td>
            <td>{!! $row->first_name.' '.$row->last_name !!}</td>
            <td>{!! $row->email !!}</td>
            <td>{!! $row->nombre_almacen !!}</td>
          
        </tr>
        @endforeach
    </tbody>
</table>
                       