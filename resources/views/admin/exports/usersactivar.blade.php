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
           
            <th><b>Direccion</b></th>
            <th><b>Barrio</b></th>
            <th><b>Ciudad</b></th>
            <th><b>Departamento</b></th>
            <th><b>Teléfono</b></th>
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
            
            <td>{{ $row->abrevia_estructura.' '.$row->principal_address.' '.$row->secundaria_address.' '.$row->edificio_address.' '.$row->detalle_address }}</td>
            <td>{{  $row->barrio_address  }}</td>
            <td>{{  $row->city_name  }}</td>
            <td>{{  $row->state_name  }}</td>
            <td>{{  $row->telefono_cliente  }}</td>
          
        </tr>
        @endforeach
    </tbody>
</table>
                       