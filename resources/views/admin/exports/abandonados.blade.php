<table class="" id="categoriastable">
    <thead>
        <tr>
            <th ><b>Id Orden</b></th>
            <th><b>Referencia</b></th>
            <th><b>Monto Total </b></th>
            <th><b>Almacen </b></th>
            
            <th><b>Nombre Cliente</b></th>
            <th><b>Email Clinete</b></th>
            <th><b>Forma de Pago</b></th>
            <th><b>Fecha de Creación</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($abandonados as $venta)
        <tr>
            <td>{!! $venta->id !!}</td>
            <td>{!! $venta->referencia !!}</td>
            <td>{!! $venta->monto_total !!}</td>
            <td>{!! $venta->nombre_almacen !!}</td>
            
            <td>{!! $venta->first_name.' '.$venta->last_name !!}</td>
            <td>{!! $venta->email !!}</td>
            <td>{!! $venta->nombre_forma_pago !!}</td>
            <td>{!! $venta->created_at !!}</td>

        </tr>
        @endforeach
    </tbody>
</table>
                       