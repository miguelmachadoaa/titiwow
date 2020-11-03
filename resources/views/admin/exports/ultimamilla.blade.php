<table class="" id="categoriastable">
    <thead>
        <tr>
            <th ><b>Direccion</b></th>
            <th ><b>Peso</b></th>
            <th ><b>Instrucciones</b></th>
            <th ><b>Nro de Orden </b></th>
            <th ><b>Contacto </b></th>
            <th ><b>Telefono </b></th>
            <th ><b>Email </b></th>
            <th ><b>Referencia Orden </b></th>
            <th ><b>Origen </b></th>
        </tr>
    </thead>
    <tbody>

        <tr>
            <td>CL 78#77B-28 LA GRANJA, Bogotá</td>
            <td>0 Grs</td>
            <td></td>
            <td>bodega</td>
            <td>bodega</td>
            <td>3003000000</td>
            <td>gerenciaoperaciones@mercadoasucasa.com</td>
        </tr>

        @foreach ($ordenes as $row)

        @if(isset($row->direccion))
            
                    <tr>
                        <td>{{ $row->direccion->abrevia_estructura.' '.$row->direccion->principal_address.'# '.$row->direccion->secundaria_address.' - '.$row->direccion->edificio_address.' '.$row->detalle_address.' '.$row->direccion->barrio_address.', '.$row->direccion->city_name      }}</td>
                        <td>{!! $row->peso.' Grs' !!}</td>
                        <td>{!! $row->direccion->notas !!}</td>
                        <td>{!! $row->id!!}</td>
                        <td>{!! $row->cliente->first_name.' '.$row->cliente->last_name!!}</td>
                        <td>{!! $row->cliente->telefono_cliente!!}</td>
                        <td>{!! $row->cliente->email!!}</td>

                         <td>{!! $row->referencia !!}</td>
                        <td>

                            @if($row->origen==0)

                            Web

                            @else

                            Tomapedidos

                            @endif


                        </td>
                        

                      
                    </tr>
                    @endif
               
        @endforeach
    </tbody>
</table>
                       