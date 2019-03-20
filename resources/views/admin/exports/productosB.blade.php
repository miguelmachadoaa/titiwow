<table class="" id="categoriastable">
    <thead>
        <tr>
            <th ><b>Id_Orden</b></th>
            <th ><b>Cod OC SAC</b></th>
            <th ><b>Id Usuario</b></th>
            <th ><b>Nombre Usuario</b></th>
            <th ><b>EAN</b></th>
            <th ><b>SKU</b></th>
            <th ><b>Producto</b></th>
            <th><b>Precio</b></th>
            <th><b>Cantidad</b></th>
            <th><b>Base de Iva</b></th>
            <th><b>Subtotal</b></th>
            <th><b>Iva</b></th>
            <th><b>Monto Iva</b></th>
            <th><b>Fecha</b></th>
            <th><b>Id Factura</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($productos as $row)
        <tr>
            <td>{!! $row->id_orden!!}</td>
            <td>{!! $row->ordencompra!!}</td>
            <td>{!! $row->doc_cliente!!}</td>
            <td>{!! $row->first_name.' '.$row->last_name!!}</td>
            <td>{!! $row->referencia_producto!!}</td>
            <td>{!! $row->referencia_producto_sap!!}</td>
            <td>{!! $row->nombre_producto !!}</td>
            <td>{!! $row->precio_unitario !!}</td>
            <td>{!! $row->cantidad !!}</td>
            <td>{!! $row->precio_total/(1+$row->valor_impuesto) !!}</td>
            <td>{!! $row->precio_total !!}</td>
            <td>{!! $row->valor_impuesto*100 !!}%</td>
            <td>{!! $row->monto_impuesto !!}</td>
            <td>{!! $row->fecha !!}</td>
            <td></td>
          
        </tr>
        @endforeach
    </tbody>
</table>
                       