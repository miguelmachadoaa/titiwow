<table class="" id="categoriastable">
    <thead>
        <tr>
            <th ><b>Id_Orden</b></th>
            <th><b>Cliente</b></th>
            <th><b>Telefono</b></th>
            <th><b>Estado Compramas</b></th>
            <th><b>Mensaje Compramas</b></th>
            <th><b>Estado de Orden</b></th>
            <th><b>Estado pago</b></th>
            <th><b>Fecha</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($ordenes as $row)
        <tr>
            <td>{!! $row->id !!}</td>
            <td>{!! $row->first_name. ' '. $row->last_name !!}</td>
            <td>{!! $row->telefono_cliente !!}</td>
            <td>{!! $row->estado_compramas !!}</td>
            <td>
                {!! $row->mensaje !!}
            </td>
            <td>{!! $row->estatus !!}</td>
            <td>{!! $row->estatus_pago !!}</td>

            <td>{!! $row->created_at !!}</td>
          
        </tr>
        @endforeach
    </tbody>
</table>
                       