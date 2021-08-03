<table >
        <tr>
            <th ><b>Cupon</b></th>
            <th ><b>Id Pedido</b></th>
            <th ><b>Usuario </b></th>
            <th><b>Cedula </b></th>
            <th><b>EAN</b></th>
            <th ><b>SKU</b></th>
            <th ><b>Producto</b></th>
            <th ><b>Presentacion</b></th>
            <th ><b>Cantidad</b></th>
            <th ><b>Total Producto</b></th>
            <th ><b>Total Descuento</b></th>
            <th ><b>Tipo Cupon</b></th>
            <th ><b>Valor Cupon</b></th>
            <th ><b>Origen Cupon</b></th>
            <th ><b>Fecha</b></th>

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
                <td>{!! $row->presentacion_producto !!}</td>
                <td>{!! $row->cantidad !!}</td>
                <td>{!! $row->precio_total !!}</td>
                <td>{!! $row->monto_descuento !!}</td>
                @if($row->tipo_reduccion == 1)
                    <td>Absoluto</td>
                @elseif($row->tipo_reduccion == 2)
                    <td>Porcentual</td>
                @endif
                <td>{!! $row->valor_cupon !!}</td>
                <td>{!! $row->origen !!}</td>
                <td>{!! $row->fecha_pedido!!}</td>
            </tr>
        @endforeach
</table>
                       