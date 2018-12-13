@component('mail::message')
Gracias por su compra <b>{{ $compra->first_name.' '.$compra->last_name }}</b>

Hemos registrado una compra {{ $compra->referencia }},  Ha seleccionado enviar el pedido con <b>{{ $compra->nombre_forma_envios }}</b> y será entregado {{ $fecha_entrega }}. 

Datos de la compra

<p><b>IdPedido: </b>{{ $compra->id }}</p>
<p><b>Cedula: </b>{{ $compra->doc_cliente }}</p>
<p><b>Valor Pagado: </b>{{ $compra->monto_total }}</p>
<p><b>Base Imponible: </b>{{ number_format($compra->base_impuesto,0,",",".")}}</p>
<p><b>Valor Iva: </b>{{ number_format($compra->monto_impuesto,0,",",".")}}</p>
<p><b>Fecha de Entrega: </b>{{ $fecha_entrega }}</p>



<h3>Detalle de compra</h3>

<table width="100%" border="1">
	<tr>
		<th>EAN</th>
		<th>Sku</th>
		<th>idProducto</th>
		<th>Producto</th>
         <th>Precio</th>
         <th>Cantidad</th>
         <th>SubTotal</th>
	</tr>

	@foreach($detalles as $row)

		<tr>
		<td>{{$row->referencia_producto}}</td>
		<td>{{$row->referencia_producto_sap}}</td>
		<td>{{$row->id_producto}}</td>
		<td>{{$row->nombre_producto}}</td>
        <td>{{number_format($row->precio_unitario,0,",",".")}}</td>
        <td> {{ $row->cantidad }} </td>
        <td>{{ number_format($row->precio_total, 0,",",".") }}</td>
	</tr>

	@endforeach

</table>
<br />
El total de la compra fué de {{ number_format($compra->monto_total, 0,",",".") }}


@component('mail::button', ['url' => secure_url('/')])
Sigue Comprando
@endcomponent


Gracias,<br>
{{ config('app.name') }}
@endcomponent
