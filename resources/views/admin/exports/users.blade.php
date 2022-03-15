<table class="" id="categoriastable">
    <thead>
        <tr>
            <th ><b>Correo</b></th>
            <th><b>  Nombre</b></th>
            <th><b>Apellido</b></th>
            <th><b>Fecha de Nacimiento</b></th>
            <th><b>Genero</b></th>

            <th><b>Tipo de Documento</b></th>
            <th><b>Identificacion</b></th>
            <th><b>Ciudad</b></th>
            <th><b>Direccion Principal  </b></th>
            <th><b>Telefono</b></th>
            <th><b>Fecha de Creacion </b></th>
            <th><b>Marketing</b></th>
            <th><b>Habeas</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($users as $row)
        <tr>
            <td>{!! $row->email !!}</td>
            <td>{!! $row->first_name!!}</td>
            <td>{!! $row->last_name !!}</td>
            <td>{!! $row->dob !!}</td>
            <td>{!! $row->genero_cliente !!}</td>
            <td>Cedula de Ciudadania</td>
            <td>{!! $row->doc_cliente !!}</td>
            <td>{!! $row->city_name !!}</td>
            @if(isset($row->dir->id))

            <td>{{ $row->dir->abrevia_estructura.' '.$row->dir->principal_address.' '.$row->dir->secundaria_address.' '.$row->dir->edificio_address.' '.$row->dir->detalle_address }}</td>


            @else

            <td>{{ $row->abrevia_estructura.' '.$row->principal_address.' '.$row->secundaria_address.' '.$row->edificio_address.' '.$row->detalle_address }}</td>


            @endif

            <td>{!! $row->telefono_cliente !!}</td>
            <td>{!! $row->created_at !!}</td>
            @if($row->marketing_email=='1')
            <td>SI</td>
            @else
            <td>NO</td>
            @endif

            @if($row->habeas_cliente=='1')
            <td>SI</td>
            @else
            <td>NO</td>
            @endif
            
          
        </tr>
        @endforeach
    </tbody>
</table>
                       