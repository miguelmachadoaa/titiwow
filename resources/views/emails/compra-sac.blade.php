@component('mail::message')

Se ha registrado un pedido por el usuario {{ $compra->first_name.' '.$compra->last_name }}

Detalles del Pedido 

<b>ID pedido:</b> {{ $compra->id }}
<b>ID usuario masterfile:</b> {{ $compra->cod_oracle_cliente }}
<b>Documento:</b> {{ 'E'.$compra->doc_cliente }}
<b>Valor Pagado:</b> {{ $compra->monto_total }}
<b>Base Impuesto:</b> {{ $compra->base_impuesto/(1+$compra->valor_impuesto) }}
<b>Valor Iva:</b> {{ $compra->monto_impuesto }}
<b>Fecha de Entrega:</b> {{ $fecha_entrega }}


<h3>Detalle de cada producto</h3>

<table width="100%" style="border-collapse: collapse;border: solid 2px #e9e9e9;" cellpadding="10px">
	<tr>
		<th style="border: solid 2px #e9e9e9;">Sku</th>
         <th style="border: solid 2px #e9e9e9;">Nombre Producto</th>
         <th style="border: solid 2px #e9e9e9;">Cantidad</th>
         <th style="border: solid 2px #e9e9e9;">Valor</th>
	</tr>

	@foreach($detalles as $row)

		<tr>
		<td style="border: solid 2px #e9e9e9;">{{$row->referencia_producto}}</td>
		<td style="border: solid 2px #e9e9e9;">{{$row->nombre_producto}}</td>
        <td style="border: solid 2px #e9e9e9;"> {{ $row->cantidad }} </td>
        <td style="border: solid 2px #e9e9e9;">{{number_format($row->precio_unitario,0,",",".")}}</td>
        
	</tr>

	@endforeach

</table>

El total de la compra fue de {{ number_format($compra->monto_total, 0,",",".") }}
El Ahorro de su compra fue  {{ number_format($compra->monto_total_base-$compra->monto_total, 0,",",".") }}


@component('mail::button', ['url' => secure_url('/admin')])
Visitar página
@endcomponent


Gracias,<br>
{{ config('app.name') }}
@endcomponent
