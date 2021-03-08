<table >
        <tr>
            <th ><b>Id Orden</b></th>
            <th ><b>Referencia </b></th>
            <th ><b>Codigo Cupon </b></th>
            <th><b>Monto Descuento </b></th>
            <th><b>Nombre</b></th>
            <th ><b>Apellido</b></th>
            <th ><b>Origen Cupon </b></th>

        </tr>

        @foreach ($cupones as $row)
            <tr>
                <td>{!! $row->id_orden!!}</td>
                <td>{!! $row->referencia!!}</td>
                <td>{!! $row->codigo_cupon!!}</td>
                <td>{!! $row->monto_descuento!!}</td>
                <td>{!! $row->first_name!!}</td>
                <td>{!! $row->last_name !!}</td>
                <td>{!! $row->origen !!}</td>
            </tr>
        @endforeach
</table>
                       