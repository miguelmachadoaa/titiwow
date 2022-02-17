<table class="" id="categoriastable">
    <thead>
        <tr>
            <th ><b>Campaña</b></th>
            <th><b> Cantidad Millas</b></th>
            <th><b>Minimo Compra</b></th>
            <th><b>Code</b></th>
            <th><b>Id Orden</b></th>
            <th><b>Monto Orden</b></th>
            <th><b>Nombre Cliente</b></th>
            <th><b>Email Cliente</b></th>
            <th><b>Fecha Asignación</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($lifemiles as $venta)
        <tr>
            <td>{!! $venta->nombre_lifemile !!}</td>
            <td>{!! $venta->cantidad_millas!!}</td>
            <td>{!! $venta->minimo_compra !!}</td>
            <td>{!! $venta->code !!}</td>
            <td>{!! $venta->id_orden !!}</td>
            <td>{!! $venta->monto_total !!}</td>
            <td>{!! $venta->first_name.' '.$venta->last_name !!}</td>
            <td>{!! $venta->email !!}</td>
            <td>{!! $venta->fecha_asignacion !!}</td>
            

        </tr>
        @endforeach
    </tbody>
</table>
                       