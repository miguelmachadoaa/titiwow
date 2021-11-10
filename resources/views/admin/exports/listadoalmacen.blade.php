<table class="" id="categoriastable">
    <thead>
        <tr>
            <th ><b>store_id</b></th>
            <th ><b>id (SKU)</b></th>
            <th ><b>gtin</b></th>
            <th ><b>name</b></th>
            <th ><b>price</b></th>
            <th ><b>stock</b></th>
            <th ><b>is_available</b></th>
            <th ><b>sale_type</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($productos as $row)

        
            <tr>
                <td>{!! $row->codigo_almacen !!}</td>
                <td>{!! $row->referencia_producto1 !!}</td>
                <td>{!! $row->referencia_producto1 !!}</td>
                <td>{!! $row->nombre_producto1 !!}</td>
                <td>{!! $row->precio_base1 !!}</td>
                @if(isset($inventario[$row->id][$almacen->id]))
                <td>{!! $inventario[$row->id][$almacen->id]!!}</td>

                @else
                <td>0</td>

                @endif
                <td>True</td>
                <td>U</td>
            </tr>
        @endforeach
    </tbody>
</table>
                       