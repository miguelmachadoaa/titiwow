@component('mail::message')
Gracias por su compra {{ $compra->first_name.' '.$compra->last_name }}

Hemos registrado una compra {{ $compra->referencia }},  Ha seleccionado enviar el pedido con <b>{{ $compra->nombre_forma_envios }}</b> y será entregado {{ $fecha_entrega }}. 

Datos de la compra

<p><b>IdPedido: </b>{{ $compra->id }}</p>
<p><b>IdUsuario Masterfile: </b>{{ $compra->cod_oracle_cliente }}</p>
<p><b>Cedula: </b>{{ $compra->doc_cliente }}</p>
<p><b>Valor Pagado: </b>{{ $compra->monto_total }}</p>
<p><b>Valor Iva: </b>{{ 0}}</p>
<p><b>Fecha de Entrega: </b>{{ $compra->fecha_envio }}</p>



<h3>Detalle de compra</h3>

<table width="100%">
	<tr>
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
		<td>{{$row->id_producto}}</td>
		<td>{{$row->nombre_producto}}</td>
        <td>{{number_format($row->precio_unitario,0,",",".")}}</td>
        <td> {{ $row->cantidad }} </td>
        <td>{{ number_format($row->precio_total, 0,",",".") }}</td>
	</tr>

	@endforeach

</table>

El total de la compra fue de {{ number_format($compra->monto_total, 0,",",".") }}


@component('mail::button', ['url' => secure_url('/')])
Sigue Comprando
@endcomponent


Gracias,<br>
{{ config('app.name') }}
@endcomponent
