<table class="" id="categoriastable">
    <thead>
        <tr>
            <th ><b>Id_Usuario</b></th>
            <th><b> Id_MasterFile</b></th>
            <th><b>Fecha_Registro</b></th>
            <th><b>Cedula</b></th>
            <th><b>Nombre</b></th>

            <th><b>Email</b></th>
            <th><b>Rol</b></th>
            <th><b>Cantidad Amigos Activos</b></th>
            <th><b>Cantidad Amigos</b></th>
            <th><b>Id_Embajador</b></th>
            <th><b>Valor Total de Ventas</b></th>
            <th><b>Numero de Pedidos</b></th>
            <th><b>Ultima Fecha de Compra</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($users as $row)
        <tr>
            <td>{!! $row->id !!}</td>
            <td>{!! $row->cod_oracle_cliente!!}</td>
            <td>{!! $row->fecha !!}</td>
            <td>{!! $row->doc_cliente !!}</td>
            <td>{!! $row->first_name.' '.$row->last_name !!}</td>
            <td>{!! $row->email !!}</td>
            <td>{!! $row->name_rol !!}</td>
            <td>{!! $row->cantidad_amigos !!}</td>
            <td>{!! $row->cantidad_amigos !!}</td>
            <td>{!! $row->id_embajador !!}</td>
            <td>{!! $row->monto_total_ordenes !!}</td>
            <td>{!! $row->cantidad_ordenes !!}</td>
            <td>{!! $row->fecha_ultima_compra !!}</td>
          
        </tr>
        @endforeach
    </tbody>
</table>
                       