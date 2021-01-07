<table class="" id="categoriastable">
    <thead>
        <tr>
            <th ><b>ID</b></th>
            <th ><b>NOMBRE DEL PRODUCTO</b></th>
            <th ><b>PRESENTACION</b></th>
            <th ><b>SKU</b></th>
            <th ><b>EAN</b></th>
            <th ><b>TIPO</b></th>
            <th ><b>URL IMAGEN </b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($productos as $row)
        <tr>
            <td>{!! $row->id !!}</td>
            <td>{!! $row->nombre_producto !!}</td>
            <td>{!! $row->presentacion_producto !!}</td>
            <td>{!! $row->referencia_producto!!}</td>
            <td>{!! $row->referencia_producto_sap!!}</td>
            @if($row->tipo_producto == 1)
            <td>Normal</td>
            @elseif($row->tipo_producto == 2)
            <td>Combo</td>
            @elseif($row->tipo_producto == 3)
            <td>Ancheta</td>
            @endif
            <td>{!! secure_url('uploads/productos/'.$row->imagen_producto) !!}</td>
          
        </tr>
        @if($row->tipo_producto == 2)
        @if(isset($row->productos))
            <tr>
                <th><b>CONTENIDO DEL COMBO</b></th>
            </tr>
            <tr>
                <th><b>Id</b></th>
                <th><b>Nombre del Producto</b></th>
                <th><b>Presentación</b></th>
                <th><b>Sku</b></th>
                <th><b>Ean</b></th>
                <th><b>Cantidad</b></th>
            </tr>

                @foreach ($row->productos as $row2)
                <tr>
                    <td>{!! $row2->id_producto !!}</td>
                    <td>{!! $row2->nombre_producto1 !!}</td>
                    <td>{!! $row2->presentacion_producto1 !!}</td>
                    <td>{!! $row2->referencia_producto1 !!}</td>
                    <td>{!! $row2->referencia_producto_sap1 !!}</td>
                    <td>{!! $row2->cantidad !!}</td>
                </tr>
                @endforeach
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>
        @endif
        @endif
        @endforeach
    </tbody>
</table>
                       