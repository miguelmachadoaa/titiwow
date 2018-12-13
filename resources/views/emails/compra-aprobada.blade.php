@component('mail::message')

Gracias por su compra {{ $compra->first_name.' '.$compra->last_name }}

Su compra {{ $compra->referencia }},  ya se encuentra en proceso de empaque. La misma sera entregada para la fecha  {{ $fecha_entrega }}. 



<h3>Detalle de compra</h3>

<table width="100%">
	<tr>
		<th>Producto</th>
         <th>Precio</th>
         <th>Cantidad</th>
         <th>SubTotal</th>
	</tr>

	@foreach($detalles as $row)

		<tr>
		<td>{{$row->nombre_producto}}</td>
        <td>{{number_format($row->precio_unitario,0,",",".")}}</td>
        <td> {{ $row->cantidad }} </td>
        <td>{{ number_format($row->precio_total, 0,",",".") }}</td>
	</tr>

	@endforeach

</table>

El total de la compra fue de {{ number_format($compra->monto_total, 0,",",".") }}
Base Imponible {{ number_format($compra->base_impuesto, 0,",",".") }}
Monto Impuesto{{ number_format($compra->monto_impuesto, 0,",",".") }}


@component('mail::button', ['url' => secure_url('/')])
Sigue Comprando
@endcomponent


Gracias,<br>
{{ config('app.name') }}
@endcomponent
