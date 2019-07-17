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
            <th><b>Monto Descuesto</b></th>
            <th><b>Codigo Cupon</b></th>
            <th><b>Ciudad</b></th>
            <th><b>Direccion</b></th>
            <th><b>Valor Total Orden</b></th>
            <th><b>Base Imponible Orden</b></th>
            <th><b>Valor Impuesto Orden</b></th>
            <th><b>Monto Impuesto Orden</b></th>
            <th><b>OBSERVACIONES</b></th>
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
            <td>   @if($row->valor_impuesto!=0) {!!  $row->precio_total/(1+$row->valor_impuesto) !!}  @else   {{ 0 }} @endif      </td>
            <td>{!! $row->precio_total !!}</td>
            <td>{!! $row->valor_impuesto*100 !!}%</td>
            <td>{!! $row->monto_impuesto !!}</td>
            <td>{!! $row->fecha !!}</td>
            <td></td>
            <td>{!! $row->monto_descuento !!}</td>
            <td>{!! $row->codigo_cupon !!}</td>
            <td>{!! $row->city_name !!}</td>
            <td>{{ $row->abrevia_estructura.' '.$row->principal_address.' '.$row->secundaria_address.' '.$row->edificio_address.' '.$row->detalle_address     }}</td>
            <td>{!! $row->monto_total_orden !!}</td>
            <td>{!! $row->base_impuesto_orden !!}</td>
            <td>{!! $row->valor_impuesto_orden !!}</td>
            <td>{!! $row->monto_impuesto_orden !!}</td>
            <td>{!! $row->barrio_address !!}</td>

          
        </tr>
        @endforeach
    </tbody>
</table>
                       