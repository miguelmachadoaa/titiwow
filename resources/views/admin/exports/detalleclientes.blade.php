<table class="" id="categoriastable">
    
        <tr>
            <td>Pedido</td>
            <td>Id_Cliente</td>
            <td>Nombre</td>
            <td>Documento Cliente</td>
            <td>Direccion</td>
            <td>Valor del Pedido </td>
            <td>Fecha del Pedido</td>
        </tr>

        @foreach ($ordenes as $orden)
        <tr>

           <td>{{$orden->id}}</td>
           <td>{{$orden->id_usuario}}</td>
           <td>{{$orden->first_name.' '.$orden->last_name}}</td>
           <td>{{$orden->doc_cliente}}</td>
             <td>{{ $orden->direccion->nombre_estructura.' '.$orden->direccion->principal_address.' - '.$orden->direccion->secundaria_address.' '.$orden->direccion->edificio_address.' '.$orden->direccion->detalle_address.' '.$orden->direccion->barrio_address }}</td>

             <td>{{$orden->monto_total}}</td>
             <td>{{$orden->fecha}}</td>


        @endforeach
    
</table>
                       