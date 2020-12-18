<table class="" id="categoriastable">
    <thead>
        <tr>
            <th ><b>first_name</b></th>
            <th><b>last_name</b></th>
            <th><b>dob</b></th>
            <th><b>genero_cliente</b></th>
            <th><b>doc_cliente</b></th>
            <th><b>telefono_cliente</b></th>
            <th><b>marketig_email</b></th>
            <th><b>marketig_sms</b></th>
            <th><b>eliminar_cliente</b></th>
            <th><b>email</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($users as $row)
        <tr>
            <td>{!! $row->first_name !!}</td>
            <td>{!! $row->last_name!!}</td>
            <td>{!! $row->dob !!}</td>
            <td>{!! $row->genero_cliente !!}</td>
            <td>{!! $row->doc_cliente !!}</td>
            <td>{!! $row->telefono_cliente !!}</td>
            <td>{!! $row->marketig_email !!}</td>
            <td>{!! $row->marketig_sms !!}</td>
            <td>0</td>
            <td>{!! $row->email !!}</td>
          
        </tr>
        @endforeach
    </tbody>
</table>
                       