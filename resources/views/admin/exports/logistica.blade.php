<table class="" id="categoriastable">
    <thead>
        <tr>
            <th ><b>Id Orden</b></th>
            <th ><b>Numero_pedido</b></th>
            <th><b> Ciudad</b></th>
            <th><b>Direccion Entrega</b></th>
            <th><b>Tipo de pago efectivo o postpago</b></th>
            <th><b>Valor Total Productos</b></th>
            <th><b>Base Imponible</b></th>
            <th><b>Valor Impuesto</b></th>
            <th><b>Monto Impuesto</b></th>
            <th><b>Nombre Cliente</b></th>
            <th><b>Documento Cliente</b></th>
            <th><b>Telefono Cliente</b></th>
            <th><b>Ruta</b></th>
            <th><b>Observaciones</b></th>
            <th><b>Fecha</b></th>
            <th><b>Origen</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($ventas as $row)
        <tr>
            <td>{!! $row->id !!}</td>
            <td>{!! $row->ordencompra !!}</td>
            <td>{!! $city[$row->direccion->city_id]!!}</td>
            <td>{{ $row->direccion->abrevia_estructura.' '.$row->direccion->principal_address.' '.$row->direccion->secundaria_address.' '.$row->direccion->edificio_address.' '.$row->direccion->detalle_address     }}</td>
            <td>3</td>
            <td>{!! $row->monto_total !!}</td>
            <td>@if($row->valor_impuesto!=0) {!! $row->base_impuesto/(1+$row->valor_impuesto) !!}  @else   {{ 0 }} @endif</td>
            <td>{!! $row->valor_impuesto*100 !!}%</td>
            <td>{!! $row->monto_impuesto !!}</td>
            <td>{!! $nombre[$row->id_cliente].' '.$apellido[$row->id_cliente] !!}</td>
            <td>{!! $documento[$row->id_cliente] !!}</td>
            <td>{!! $telefono[$row->id_cliente] !!}</td>
            <td></td>
            <td>{!! $row->barrio_address !!}</td>
            <td>{!! $row->fecha !!}</td>
           <td>

                    @if($row->origen==0)

                    Web

                    @else

                    Tomapedidos

                    @endif


                </td>
          
        </tr>
        @endforeach
    </tbody>
</table>
                       