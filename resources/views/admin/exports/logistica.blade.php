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
            <td>{!! $row->city_name!!}</td>
            <td>{{ $row->abrevia_estructura.' '.$row->principal_address.' '.$row->secundaria_address.' '.$row->edificio_address.' '.$row->detalle_address     }}</td>
            <td>3</td>
            <td>{!! $row->monto_total !!}</td>
            <td>@if($row->valor_impuesto!=0) {!! $row->base_impuesto/(1+$row->valor_impuesto) !!}  @else   {{ 0 }} @endif</td>
            <td>{!! $row->valor_impuesto*100 !!}%</td>
            <td>{!! $row->monto_impuesto !!}</td>
            <td>{!! $row->first_name.' '.$row->last_name !!}</td>
            <td>{!! $row->doc_cliente !!}</td>
            <td>{!! $row->telefono_cliente !!}</td>
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
                       