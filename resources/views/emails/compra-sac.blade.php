@component('mail::message')

Se ha registrado un pedido por el usuario {{ $compra->first_name.' '.$compra->last_name }}

Detalles del Pedido 

<b>ID pedido:</b> {{ $compra->id }}
<b>ID usuario masterfile:</b> {{ $compra->cod_oracle_cliente }}
<b>Cedula:</b> {{ $compra->doc_cliente }}
<b>Valor Pagado:</b> {{ $compra->monto_total }}
<b>Valor Iva:</b> {{ 0 }}
<b>Fecha de Entrega:</b> {{ $fecha_entrega }}





<h3>Detalle de cada producto</h3>

<table width="100%">
	<tr>
		<th>Sku</th>
         <th>Nombre Producto</th>
         <th>Cantidad</th>
         <th>Valor</th>
	</tr>

	@foreach($detalles as $row)

		<tr>
		<td>{{$row->pum}}</td>
		<td>{{$row->nombre_producto}}</td>
        <td>{{number_format($row->precio_unitario,0,",",".")}}</td>
        <td> {{ $row->cantidad }} </td>
        
	</tr>

	@endforeach

</table>

El total de la compra fue de {{ number_format($compra->monto_total, 0,",",".") }}


@component('mail::button', ['url' => secure_url('/')])
Visitar pagina
@endcomponent


Gracias,<br>
{{ config('app.name') }}
@endcomponent
