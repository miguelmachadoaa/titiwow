<table >
        <tr>
            <th ><b>Cupon</b></th>
            <th ><b>Id Pedido</b></th>
            <th ><b>Usuario </b></th>
            <th><b>Cedula </b></th>
            <th><b>EAN</b></th>
            <th ><b>SKU</b></th>
            <th ><b>Producto</b></th>
            <th ><b>Cantidad</b></th>
            <th ><b>Total Producto</b></th>
            <th ><b>Total Descuento</b></th>

        </tr>

        @foreach ($productos as $row)
            <tr>
                <td>{!! $row->codigo_cupon!!}</td>
                <td>{!! $row->id_orden!!}</td>
                <td>{!! $row->first_name.' '.$row->last_name !!}</td>
                <td>{!! $row->doc_cliente !!}</td>
                <td>{!! $row->referencia_producto !!}</td>
                <td>{!! $row->referencia_producto_sape !!}</td>
                <td>{!! $row->nombre_producto !!}</td>
                <td>{!! $row->cantidad !!}</td>
                <td>{!! $row->precio_total !!}</td>
                <td>{!! $row->monto_descuento !!}</td>
            </tr>
        @endforeach
</table>
                       