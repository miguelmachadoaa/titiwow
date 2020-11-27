<table class="" id="categoriastable">
    <thead>
        <tr>
            <th><b>Id</b></th>

            <th ><b>Nombre</b></th>
            <th ><b>Presentacion</b></th>
            <th ><b>EAN</b></th>
            <th ><b>Referencia</b></th>
            <th><b>Disponible</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($productos as $row)
        <tr>
            <td>{!! $row->id !!}</td>
            <td>{!! $row->nombre_producto !!}</td>
            <td>{!! $row->presentacion_producto !!}</td>
            <td>{!! $row->referencia_producto!!}</td>
            <td>{!! $row->referencia_producto_sap !!}</td>

            @if(isset($inventario[$row->id]))
            <td>{!! $inventario[$row->id]!!}</td>
            @else
            <td>0</td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>
                       