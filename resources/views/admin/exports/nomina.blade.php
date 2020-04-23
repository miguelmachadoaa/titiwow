<table class="" id="categoriastable">
    <thead>
        <tr>
            <th ><b>id Pedido</b></th>
            <th ><b>Cedula</b></th>
            <th ><b>Código</b></th>
            <th ><b>Nombre </b></th>
            <th ><b>Sku </b></th>
            <th ><b>Producto </b></th>
            <th ><b>Cantidad </b></th>
            <th ><b>Creado  </b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($ordenes as $row)
        <tr>
            <td>{!! $row->id !!}</td>
            <td>{!! $row->doc_cliente!!}</td>
            <td>{!! $row->cod_oracle_cliente!!}</td>
            <td>{!! $row->first_name.' '.$row->last_name!!}</td>
            <td>{!! $row->referencia_producto!!}</td>
            <td>{!! $row->nombre_producto!!}</td>
            <td>{!! $row->cantidad!!}</td>
            <td>{!! $row->created_at!!}</td>
          
        </tr>
        @endforeach
    </tbody>
</table>
                       