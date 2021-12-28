<table class="" id="categoriastable">
    <thead>
        <tr>
            <th ><b>Id</b></th>
            <th><b>Nombre</b></th>
            <th><b>Apellido</b></th>
            <th><b>Correo</b></th>
            <th><b>Cantidad Compras</b></th>
            <th><b>Total Compras</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($usercompras as $venta)
        <tr>
            <td>{!! $venta->id !!}</td>
            <td>{!! $venta->first_name!!}</td>
            <td>{!! $venta->last_name !!}</td>
            <td>{!! $venta->email !!}</td>
            <td>{!! $venta->cantidad_compras !!}</td>
            <td>{!! $venta->total_compras !!}</td>

        </tr>
        @endforeach
    </tbody>
</table>
                       