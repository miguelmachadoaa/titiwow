<table class="" id="categoriastable">
    <thead>
        <tr>
            <th ><b>id Pedido</b></th>
            <th ><b>Cedula</b></th>
            <th ><b>Código</b></th>
            <th ><b>Nombre </b></th>
            <th ><b>Sku </b></th>
            <th ><b>Ean </b></th>
            <th ><b>Producto </b></th>
            <th ><b>Cantidad </b></th>
            <th ><b>Creado  </b></th>
            <th ><b>Ciudad  </b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($ordenes as $row)
            @if(count($row->detalles))
                @foreach ($row->detalles as $detalle)
                    <tr>
                        <td>{!! $row->id !!}</td>
                        <td>{!! $row->cliente->doc_cliente!!}</td>
                        <td>{!! $row->cliente->cod_oracle_cliente!!}</td>
                        <td>{!! $row->cliente->first_name.' '.$row->cliente->last_name!!}</td>
                        <td>{!! $detalle->referencia_producto_sap!!}</td>
                        <td>{!! $detalle->referencia_producto!!}</td>
                        <td>{!! $detalle->nombre_producto!!}</td>
                        <td>{!! $detalle->cantidad!!}</td>
                        <td>{!! date("d/m/Y H:i:s", strtotime($row->created_at))!!}</td>
                        <td>{!! $row->direccion->city_name !!}</td>

                      
                    </tr>
                @endforeach
            @endif
        @endforeach
    </tbody>
</table>
                       