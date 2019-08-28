<table class="" id="categoriastable">
    <thead>
        <tr>
            <th ><b>Fecha_Venta</b></th>
            <th><b> Id_Pedido</b></th>
            <th><b>Id_Factura</b></th>
            <th><b>Id_ClienteMasterfile</b></th>
            <th><b>Cedula</b></th>
            <th><b>Nombre</b></th>
            <th><b>Id_Mercadopago</b></th>
            <th><b>Orden_pedido_sac</b></th>
            <th><b>Medio Pago</b></th>
            <th><b>Tipo de Pago</b></th>
            <th><b>Monto Descuento</b></th>
            <th><b>ValorPedido</b></th>
            <th><b>Base Imponible</b></th>
            <th><b>Valor Iva</b></th>
            <th><b>Monto Iva</b></th>
            <th><b>Comision Mercadopago</b></th>
            <th><b>Retencion Fuente Mercadopago</b></th>
            <th><b>Retencion IVA Mercadopago</b></th>
            <th><b>Retencion ICA Mercadopago</b></th>
            <th><b>Total a Transferir</b></th>
            <th><b>Embajador</b></th>
            <th><b>Empresa Asociada</b></th>
            <th><b>Ip</b></th>

        </tr>
    </thead>
    <tbody>

        @foreach ($ventas as $row)

        @php

        $total = $row->monto_total;
        $iva=19;

        $precioIva = ($total*$iva/100);

        $precioNormalizado = floatval(sprintf($precioIva));



        @endphp
        <tr>
            <td>{!! $row->fecha !!}</td>
            <td>{!! $row->id!!}</td>
            <td>{!! $row->factura!!}</td>
            <td>{!! $row->cod_oracle_cliente!!}</td>
            <td>{!! $row->doc_cliente !!}</td>
            <td>{!! $row->first_name.' '.$row->last_name !!}</td>
            <td> 
                @if($row->json!=null) 
                    @if(isset(json_decode($row->json)->merchant_order_id ))
                        {!! json_decode($row->json)->merchant_order_id !!} 
                    @else 
                        {!! json_decode($row->json)->response->id !!} 

                    @endif 
                @endif 
            </td>
            <td>{!! $row->ordencompra !!}</td>
            <td>{!! $row->nombre_forma_pago  !!}</td>
            <td>

                @if($row->json)


                    @if(isset(json_decode($row->json)->payment_type ))
                        {!! json_decode($row->json)->payment_type !!} 
                    @else 
                        {!! json_decode($row->json)->response->payment_type_id   !!} 

                    @endif

                @endif

            </td>

            <td>{!! $row->monto_descuento !!}</td>
            <td>{!! $row->monto_total !!}</td>
            <td>@if($row->valor_impuesto!=0) {!!  $row->base_impuesto/(1+$row->valor_impuesto) !!}  @else   {{ 0 }} @endif </td>
            <td>{!! $row->valor_impuesto*100 !!} %</td>
            <td>{!! $row->monto_impuesto !!}</td>
            <td>{!! $row->comision_mp !!}</td>
            <td>{!! $row->retencion_fuente_mp !!}</td>
            <td>{!! $row->retencion_iva_mp !!}</td>
            <td>{!! $row->retencion_ica_mp !!}</td>
            <td>{!! $row->monto_total-$row->comision_mp-$row->retencion_fuente_mp-$row->retencion_iva_mp-$row->retencion_ica_mp  !!}</td>
            @if($row->id_embajador == 0)

                <td>No aplica</td>

            @else

                @if(isset($embajadores[$row->id_embajador]))


                <td>{!! $embajadores[$row->id_embajador]->first_name.' '.$embajadores[$row->id_embajador]->last_name !!}</td>

                @else

                <td>No aplica</td>


                @endif

            @endif

                
                @if(isset($empresas[$row->id_empresa]))

                <td>{!! $empresas[$row->id_empresa]->nombre_empresa !!}</td>
                        

                @else

                <td>No Aplica</td>

                @endif

                <td>{!! $row->ip !!}</td>
          
        </tr>
        @endforeach
    </tbody>
</table>
                       