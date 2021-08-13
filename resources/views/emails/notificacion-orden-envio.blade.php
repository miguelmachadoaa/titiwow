@component('mail::message')

Notificación de Creacion de envio en Compramas

 {{ $texto }}

Id de Orden: {{$orden->id}}

Fecha de Orden: {{$orden->created_at}}

<br>

	IdPedido: {{ $orden->id }}

	Documento: {{ $orden->doc_cliente }}

	Valor Pagado: {{ number_format($orden->monto_total,0,",",".") }}

	Base Impuesto: {{ number_format($orden->base_impuesto,0,",",".")}}
	
	Valor Iva: {{ number_format($orden->monto_impuesto,0,",",".")}}


<h3>Detalle de compra</h3>

<table width="100%" style="border-collapse: collapse;border: solid 2px #e9e9e9;" cellpadding="10px">
	<tr>
		<th style="border: solid 2px #e9e9e9;">EAN</th>
		<th style="border: solid 2px #e9e9e9;">Sku</th>
		<th style="border: solid 2px #e9e9e9;">idProducto</th>
		<th style="border: solid 2px #e9e9e9;">Producto</th>
		<th style="border: solid 2px #e9e9e9;">Presentación</th>
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
		<td style="border: solid 2px #e9e9e9;">{{$row->presentacion_producto}}</td>
        <td style="border: solid 2px #e9e9e9;">{{number_format($row->precio_unitario,0,",",".")}}</td>
        <td style="border: solid 2px #e9e9e9;"> {{ $row->cantidad }} </td>
        <td style="border: solid 2px #e9e9e9;">{{ number_format($row->precio_unitario*$row->cantidad, 0,",",".") }}</td>
	</tr>

	@endforeach

</table>


@if(is_null($compra->token))

@else

@component('mail::button', ['url' =>  secure_url('/tracking/'.$compra->token)])
	Rastrea tu Pedido
@endcomponent

@endif

	Gracias,<br>
	{{ config('app.name') }}

@endcomponent
