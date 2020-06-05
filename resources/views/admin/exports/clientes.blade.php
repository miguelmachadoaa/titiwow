<table class="" id="categoriastable">
    <thead>
        <tr>
            <td>Nombre </td>
            <td>Apellido</td>
            <td>Documento</td>
            <td>Departamento</td>
            <td>Ciudad</td>
            <td>Telefono</td>
            <td>Correo</td>
        </tr>
    </thead>
    <tbody>

        @foreach ($usuarios as $row)
        <tr>
           <td>{{$row->first_name}} </td>
           <td>{{$row->last_name}} </td>
           <td>{{$row->doc_cliente}} </td>
           <td>{{$row->state_name}} </td>
           <td>{{$row->city_name}} </td>
           <td>{{$row->telefono_cliente}} </td>
           <td>{{$row->email}} </td>
            
          
        </tr>
        @endforeach
    </tbody>
</table>
                       