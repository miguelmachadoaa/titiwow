<table class="" id="categoriastable">
    <thead>
        <tr>
            <th><b>ID</b></th>

            <th ><b>Nombre </b></th>
            <th ><b>Referencia</b></th>
            <th ><b>Referencia sap</b></th>
            <th ><b>Existencia</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($productos as $row)
        <tr>
            <td>{!! $row->id !!}</td>

            <td>{!! $row->nombre_producto !!}</td>
            <td>{!! $row->referencia_producto!!}</td>
            <td>{!! $row->referencia_producto_sap !!}</td>
            <td>{!! $this->inv[$row->id]!!}</td>
          
        </tr>
        @endforeach
    </tbody>
</table>
                       