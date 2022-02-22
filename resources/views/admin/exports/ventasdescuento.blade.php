<table class="" id="categoriastable">
    <thead>
        <tr>
            <th><b> Id_Pedido</b></th>
            <th><b>Nombre</b></th>
            <th><b>Cedula</b></th>
            <th><b>Cantidad productos</b></th>
            <th><b>Total Productos</b></th>
            <th><b> Codigo Cupon </b></th>
            <th><b> % Descuento </b></th>
            <th><b>Valor Descuento</b></th>
            <th><b>Fecha</b></th>
            <th><b>Email</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($ventas as $venta)
        @if(is_null($venta->codigo_cupon))
        @else
        <tr>
            <td>{!! $venta->id!!}</td>
            <td>{!! $venta->first_name.' '.$venta->last_name !!}</td>
            <td>{!! $venta->doc_cliente !!}</td>
            <td>{!! $venta->total_articulos !!}</td>
            <td>{!! $venta->monto_total !!}</td>
            <td>{!! $venta->codigo_cupon !!}</td>
            <td>{!! number_format((($venta->monto_descuento/$venta->monto_total)*100),2).'%'; !!}</td>
            <td>{!! $venta->monto_descuento !!}</td>
            <td>{!! $venta->fecha !!}</td>
            <td>{!! $venta->email !!}</td>
            
          
        </tr>
        @endif
        @endforeach
    </tbody>
</table>
                       