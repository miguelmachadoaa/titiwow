<table class="" id="categoriastable">
    <thead>
        <tr>
            <th><b>Id</b></th>

            <th ><b>Codigo</b></th>
            <th ><b>Usos</b></th>
            <th ><b>Ordens</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($cupones as $row)
        <tr>
            <td>{!! $row->id !!}</td>
            <td>{!! $row->codigo_cupon !!}</td>
            <td>{!! count($row->usos) !!}</td>
            <td>

                @foreach($row->usos as $u)

                    {{$u->id_orden.','}}

                @endforeach


            </td>
           
        </tr>
        @endforeach
    </tbody>
</table>
                       