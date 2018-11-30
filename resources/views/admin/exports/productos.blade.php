<table class="" id="categoriastable">
    <thead>
        <tr>
            <th ><b>Id_Detalle</b></th>
            <th ><b>Id_Orden</b></th>
            <th ><b>id_producto</b></th>
            <th><b>Producto</b></th>
            <th><b>Cantidad</b></th>
            <th><b>Fecha</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($productos as $row)
        <tr>
            <td>{!! $row->id !!}</td>
            <td>{!! $row->id_orden!!}</td>
            <td>{!! $row->id_producto!!}</td>
            <td>{!! $row->nombre_producto !!}</td>
            <td>{!! $row->cantidad !!}</td>
            <td>{!! $row->fecha !!}</td>
          
        </tr>
        @endforeach
    </tbody>
</table>
                       