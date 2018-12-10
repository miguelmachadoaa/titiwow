<table class="" id="categoriastable">
    <thead>
        <tr>
            <th ><b>Fecha_Venta</b></th>
            <th><b> Id_Pedido</b></th>
            <th><b>Id_Factura</b></th>
            <th><b>Id_ClienteMasterfile</b></th>
            <th><b>Cedula</b></th>
            <th><b>Nombre</b></th>
            <th><b>Id_Mercadopago</b></th>
            <th><b>Orden_pedido_sac</b></th>
            <th><b>Medio Pago</b></th>
            <th><b>ValorPedido</b></th>
            <th><b>Iva</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($ventas as $row)
        <tr>
            <td>{!! $row->fecha !!}</td>
            <td>{!! $row->id!!}</td>
            <td>{!! $row->factura!!}</td>
            <td>{!! $row->cod_oracle_cliente!!}</td>
            <td>{!! 'E'.$row->doc_cliente !!}</td>
            <td>{!! $row->first_name.' '.$row->last_name !!}</td>
            <td> @if($row->json!=null) {!! json_decode($row->json)->merchant_order_id !!} @endif </td>
            <td>{!! $row->ordencompra !!}</td>
            <td>{!! $row->nombre_forma_pago  !!}</td>
            <td>{!! $row->monto_total !!}</td>
            <td>{!! 0 !!}</td>
          
        </tr>
        @endforeach
    </tbody>
</table>
                       