<table class="" id="categoriastable">
    <thead>
        <tr>
            <th ><b>Numero_pedido</b></th>
            <th><b> Ciudad</b></th>
            <th><b>Direccion Entrega</b></th>
            <th><b>Tipo de pago efectivo o postpago</b></th>
            <th><b>Valor Total Productos</b></th>
            <th><b>Nombre Cliente</b></th>
            <th><b>Telefono Cliente</b></th>
            <th><b>Ruta</b></th>
            <th><b>Observaciones</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($ventas as $row)
        <tr>
            <td>{!! $row->ordencompra !!}</td>
            <td>{!! $row->city_name!!}</td>
            <td>{{ $row->abrevia_estructura.' '.$row->principal_address.' '.$row->secundaria_address.' '.$row->edificio_address     }}</td>
            <td>3</td>
            <td>{!! $row->monto_total !!}</td>
            <td>{!! $row->first_name.' '.$row->last_name !!}</td>
            <td>{!! $row->telefono_cliente !!}</td>
            <td></td>
            <td>{!! $row->barrio_address !!}</td>
          
        </tr>
        @endforeach
    </tbody>
</table>
                       