<table class="" id="categoriastable">
    <thead>
        <tr>
            <th ><b>Slug</b></th>
            <th><b> Sku</b></th>
            <th><b>Nombre Producto</b></th>
            <th><b>Inventario Disponible</b></th>
            <th><b>Tipo (No Modificar este campo)</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($productos as $p)
        <tr>
            <td>{!! $p['slug']  !!}</td>
            <td>{!! $p['sku'] !!}</td>
            <td>{!! $p['nombre']  !!}</td>
            <td>{!! $p['inventario']  !!}</td>
            <td>{!! $p['tipo'] !!}</td>
        </tr>
        @endforeach
    </tbody>
</table>
                       