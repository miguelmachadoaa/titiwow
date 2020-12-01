<table class="" id="categoriastable">
    <thead>
        <tr>
            <th><b>Id</b></th>

            <th ><b>Nombre</b></th>
            <th ><b>Presentacion</b></th>
            <th ><b>EAN</b></th>
            <th ><b>Referencia</b></th>
            <th ><b>Almacen</b></th>
        </tr>
    </thead>

    <tbody>



        @foreach ($almacenes as $a)

        @foreach ($a->producto as $row)
        <tr>
            <td>{!! $row->id !!}</td>
            <td>{!! $row->nombre_producto !!}</td>
            <td>{!! $row->presentacion_producto !!}</td>
            <td>{!! $row->referencia_producto!!}</td>
            <td>{!! $row->referencia_producto_sap !!}</td>
            <td>{!! $a->nombre_almacen !!}</td>
           
        </tr>


         <tr>
            <td> </td>
            <td> </td>
            <td> </td>
            <td> </td>
            <td> </td>
           
        </tr>


        @if(count($row->inventario))

            <tr>
                <td>Producto</td>
                <td>Almacen</td>
                <td>Cantidad</td>
                <td>
                    Operación
                </td>
                <td>Notas</td>
                <td>Fecha</td>
               
            </tr>



            @foreach($row->inventario as $i)

            <tr>
                <td>{{$row->nombre_producto}}</td>
                <td>{{$a->nombre_almacen}}</td>
                <td>{{$i->cantidad}}</td>
                <td>

                    @if($i->operacion=='1')
                    AGREGAR
                    @else
                    DESCONTAR
                    @endif
                    </td>
                <td>{{$i->notas}}</td>
                <td>{{$i->created_at}}</td>
               
            </tr>


            @endforeach

        @endif




        @endforeach
        @endforeach
    </tbody>
</table>
                       