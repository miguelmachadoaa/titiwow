<table class="" id="categoriastable">
    <thead>
        <tr>
            <th ><b>NOMBRE DEL PRODUCTO</b></th>
            <th ><b>PRESENTACION</b></th>
            <th ><b>SKU</b></th>
            <th ><b>EAN</b></th>
            <th ><b>INVENATARIO </b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($productos as $row)
        <tr>
            <td>{!! $row->nombre_producto !!}</td>
            <td>{!! $row->presentacion_producto !!}</td>
            <td>{!! $row->referencia_producto!!}</td>
            <td>{!! $row->referencia_producto_sap!!}</td>
            <td>
                {!! $row->inventario!!}

            </td>
          
        </tr>
        @endforeach
    </tbody>
</table>
                       