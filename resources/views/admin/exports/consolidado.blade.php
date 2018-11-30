<table class="" id="categoriastable">
    <thead>
        <tr>
            <th ><b>Id_Usuario</b></th>
            <th><b> Id_Pedido</b></th>
            <th><b>Fecha_Compra</b></th>
            <th><b>Nombre</b></th>
            <th><b>Cedula</b></th>
            <th><b>Correo</b></th>
            <th><b>Cantidad_productos</b></th>
            <th><b>Valor_Pagado</b></th>
            <th><b>Rol</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($ventas as $venta)
        <tr>
            <td>{!! $venta->id_usuario !!}</td>
            <td>{!! $venta->id!!}</td>
            <td>{!! $venta->fecha !!}</td>
            <td>{!! $venta->first_name.' '.$venta->last_name !!}</td>
            <td>{!! $venta->doc_cliente !!}</td>
            <td>{!! $venta->email !!}</td>
            <td>{!! $venta->total_articulos !!}</td>
            <td>{!! $venta->monto_total !!}</td>
            <td>{!! $venta->name_rol !!}</td>
          
        </tr>
        @endforeach
    </tbody>
</table>
                       