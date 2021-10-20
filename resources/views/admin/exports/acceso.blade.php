<table class="" id="categoriastable">
    <thead>
        <tr>
            <th><b>Nombre Admin</b></th>
            <th ><b>Rol Admin</b></th>
            <th><b>Email Admin</b></th>
            <th><b>Fecha Creación</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($acceso as $venta)
        <tr>
            <td>{!! $venta->first_name.' '.$venta->last_name !!}</td>
            <td>{!! $venta->name_rol !!}</td>
            <td>{!! $venta->email !!}</td>
            <td>{!! $venta->created_at !!}</td>

        </tr>
        @endforeach
    </tbody>
</table>
                       