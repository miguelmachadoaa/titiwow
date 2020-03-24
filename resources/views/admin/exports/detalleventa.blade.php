<table class="" id="categoriastable">
    
  

        @foreach ($ordenes as $orden)
        <tr>

             <td><b>Nombre</b></td>
           <td>{{$orden->first_name.' '.$orden->last_name}}</td>
           
           <td><b>Id Orden</b></td>
           <td>{{$orden->id}}</td>

            <td><b>Fecha</b></td>
           <td>{{$orden->created_at}}</td>    
          
        </tr>

         <tr>
             <td><b>Direccion de Envio</b> </td>
             <td>{{ $orden->direccion->nombre_estructura.' '.$orden->direccion->principal_address.' - '.$orden->direccion->secundaria_address.' '.$orden->direccion->edificio_address.' '.$orden->direccion->detalle_address.' '.$orden->direccion->barrio_address }}</td>
         </tr>

        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

            <tr>
                 <th><b>Referencia</b></th>
                 <th><b>Producto</b></th>
                 <th><b>Cantidad</b></th>
             </tr>



            @foreach($orden->detalles as $detalle)

                 <tr>
                    
                    <td>{{$detalle->referencia_producto}}</td>
                    <td>{{$detalle->nombre_producto}}</td>
                    <td>
                        {{ $detalle->cantidad }}

                    </td>
                   
                </tr>

            @endforeach

            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
    
</table>
                       