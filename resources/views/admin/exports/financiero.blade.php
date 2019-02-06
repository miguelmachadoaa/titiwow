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
            <th><b>Tipo de Pago</b></th>
            <th><b>ValorPedido</b></th>
            <th><b>Base Imponible</b></th>
            <th><b>Valor Iva</b></th>
            <th><b>Monto Iva</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($ventas as $row)

        @php

        $total = $row->monto_total;
        $iva=19;

        $precioIva = ($total*$iva/100);

        $precioNormalizado = floatval(sprintf($precioIva));



        @endphp
        <tr>
            <td>{!! $row->fecha !!}</td>
            <td>{!! $row->id!!}</td>
            <td>{!! $row->factura!!}</td>
            <td>{!! $row->cod_oracle_cliente!!}</td>
            <td>{!! $row->doc_cliente !!}</td>
            <td>{!! $row->first_name.' '.$row->last_name !!}</td>
            <td> @if($row->json!=null) {!! json_decode($row->json)->merchant_order_id !!} @endif </td>
            <td>{!! $row->ordencompra !!}</td>
            <td>{!! $row->nombre_forma_pago  !!}</td>
            <td>

                @if($row->json)

                    {{  json_decode($row->json)->payment_type }}

                @endif

            </td>

            <td>{!! $row->monto_total !!}</td>
            <td>{!! $row->base_impuesto !!}</td>
            <td>{!! $row->valor_impuesto*100 !!} %</td>
            <td>{!! $row->monto_impuesto !!}</td>
          
        </tr>
        @endforeach
    </tbody>
</table>
                       