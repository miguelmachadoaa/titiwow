<table class="" id="categoriastable">
    <thead>
        <tr>
            <th ><b>Nombre</b></th>
            <th ><b>Presentacion</b></th>
            <th ><b>Referencia</b></th>
            <th ><b>Referencia Sap</b></th>
            <th ><b>Descripción Corta</b></th>
            <th><b>Descripción Larga</b></th>
            <th><b>Video Youtube</b></th>
            <th><b>Categoria por Defecto</b></th>
            <th><b>Marca </b></th>
            <th><b>Medida</b></th>
            <th><b>Unidad de Medida</b></th>
            <th><b>Cantidad del Producto</b></th>
            <th><b>No Modificar Este Campo</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($productos as $row)
        <tr>
            <td>{!! $row->nombre_producto !!}</td>
            <td>{!! $row->presentacion_producto!!}</td>
            <td>{!! $row->referencia_producto!!}</td>
            <td>{!! $row->referencia_producto_sap!!}</td>
            <td>{!! $row->descripcion_corta!!}</td>
            <td>{!! $row->descripcion_larga !!}</td>
            <td>{{ utf8_decode(utf8_encode($row->enlace_youtube)) }}</td>
            <td>{!! $row->id_categoria_default !!}</td>
            <td>{!! $row->id_marca !!}</td>
            <td>{!! $row->medida !!}</td>
            <td>{!! $row->unidad !!}</td>
            <td>{!! $row->cantidad !!}</td>
            <td>{!! $row->id !!}</td>
          
        </tr>
        @endforeach
    </tbody>
</table>
                       