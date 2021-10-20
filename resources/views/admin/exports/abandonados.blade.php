<table class="" id="categoriastable">
    <thead>
        <tr>
            <th ><b>Referencia</b></th>
            <th><b>Nombre Cliente</b></th>
            <th><b>Email Cliente</b></th>
            <th><b>Fecha Creación</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($abandonados as $venta)
        <tr>
            <td>{!! $venta->referencia !!}</td>
            <td>{!! $venta->first_name.' '.$venta->last_name !!}</td>
            <td>{!! $venta->email !!}</td>
            <td>{!! $venta->created_at !!}</td>

        </tr>
        @endforeach
    </tbody>
</table>
                       