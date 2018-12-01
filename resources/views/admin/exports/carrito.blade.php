<table class="" id="categoriastable">
    <thead>
        <tr>
            <th><b>Fecha</b></th>

            <th ><b>id_usuario</b></th>
            <th ><b>Nombre</b></th>
            <th ><b>Email</b></th>
            <th ><b>Valor Pedido</b></th>
            <th><b>Nombres Productos</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($carrito as $row)
        <tr>
            <td>{!! $row->fecha !!}</td>

            <td>{!! $row->id_user !!}</td>
            <td>{!! $row->first_name.' '.$row->last_name !!}</td>
            <td>{!! $row->email !!}</td>
            <td>{!! $row->total_venta!!}</td>
            <td>{!! $row->nombre_productos !!}</td>
          
        </tr>
        @endforeach
    </tbody>
</table>
                       