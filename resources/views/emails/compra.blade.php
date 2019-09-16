@component('mail::message')
Gracias por su compra <b>{{ $compra->first_name.' '.$compra->last_name }}</b>

Hemos registrado una compra {{ $compra->referencia }},  Ha seleccionado enviar el pedido con <b>{{ $compra->nombre_forma_envios }}</b> y será entregado {{ $fecha_entrega }}. 

Datos de la compra



@if($envio->costo>0)


<p><b>IdPedido: </b>{{ $compra->id }}</p>
<p><b>Documento: </b>{{ $compra->doc_cliente+$envio->costo_envio  }}</p>
<p><b>Valor Pagado: </b>{{ $compra->monto_total+$envio->costo_envio }}</p>
<p><b>Base Impuesto: </b>{{ number_format(($compra->base_impuesto/(1+$compra->valor_impuesto)+$envio->costo_base),0,",",".")}}</p>
<p><b>Valor Iva: </b>{{ number_format($compra->monto_impuesto+$envio->costo_impuesto,0,",",".")}}</p>
<p><b>Fecha de Entrega: </b>{{ $fecha_entrega }}</p>


@else


<p><b>IdPedido: </b>{{ $compra->id }}</p>
<p><b>Documento: </b>{{ $compra->doc_cliente }}</p>
<p><b>Valor Pagado: </b>{{ $compra->monto_total }}</p>
<p><b>Base Impuesto: </b>{{ number_format($compra->base_impuesto/(1+$compra->valor_impuesto),0,",",".")}}</p>
<p><b>Valor Iva: </b>{{ number_format($compra->monto_impuesto,0,",",".")}}</p>
<p><b>Fecha de Entrega: </b>{{ $fecha_entrega }}</p>

@endif






<h3>Detalle de compra</h3>

<table width="100%" style="border-collapse: collapse;border: solid 2px #e9e9e9;" cellpadding="10px">
	<tr>
		<th style="border: solid 2px #e9e9e9;">EAN</th>
		<th style="border: solid 2px #e9e9e9;">Sku</th>
		<th style="border: solid 2px #e9e9e9;">idProducto</th>
		<th style="border: solid 2px #e9e9e9;">Producto</th>
        <th style="border: solid 2px #e9e9e9;">Precio</th>
        <th style="border: solid 2px #e9e9e9;">Cantidad</th>
        <th style="border: solid 2px #e9e9e9;">SubTotal</th>
	</tr>

	@foreach($detalles as $row)

		<tr>
		<td style="border: solid 2px #e9e9e9;">{{$row->referencia_producto}}</td>
		<td style="border: solid 2px #e9e9e9;">{{$row->referencia_producto_sap}}</td>
		<td style="border: solid 2px #e9e9e9;">{{$row->id_producto}}</td>
		<td style="border: solid 2px #e9e9e9;">{{$row->nombre_producto}}</td>
        <td style="border: solid 2px #e9e9e9;">{{number_format($row->precio_unitario,0,",",".")}}</td>
        <td style="border: solid 2px #e9e9e9;"> {{ $row->cantidad }} </td>
        <td style="border: solid 2px #e9e9e9;">{{ number_format($row->precio_total, 0,",",".") }}</td>
	</tr>

	@endforeach

</table>
<br />



@if($envio->costo>0)

El Costo del envio fue de {{ number_format($envio->costo, 0,",",".") }}
El total de la compra fue de {{ number_format($compra->monto_total+$envio->costo, 0,",",".") }}
El Ahorro de su compra fue  {{ number_format($compra->monto_total_base-$compra->monto_total, 0,",",".") }}

@else

El Costo del envio fue Gratis
El total de la compra fue de {{ number_format($compra->monto_total, 0,",",".") }}
El Ahorro de su compra fue  {{ number_format($compra->monto_total_base-$compra->monto_total, 0,",",".") }}


@endif









@component('mail::button', ['url' => secure_url('/')])
Sigue Comprando
@endcomponent


Gracias,<br>
{{ config('app.name') }}
@endcomponent
