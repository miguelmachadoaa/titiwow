@component('mail::message')

Se ha registrado un pedido por el usuario {{ $compra->first_name.' '.$compra->last_name }}


<b>Datos Del Clliente</b>


<b>Nombre: </b> {{ $compra->first_name.' '.$compra->last_name }}<br>
<b>Documento: </b> {{ $cliente->doc_cliente }}<br>
<b>Teléfono</b> {{$cliente->telefono_cliente}}<br>

<b>Direccion de Envio</b>

 <p>{{ $direccion->titulo   }} </p>
<p>{{ $direccion->state_name.' , '.$direccion->city_name   }}</p>
<p>{{ $direccion->nombre_estructura.' '.$direccion->principal_address .' #'. $direccion->secundaria_address .'-'.$direccion->edificio_address.', '.$direccion->detalle_address.', '.$direccion->barrio_address }}</p>
<p>{{ $direccion->notas }}</p>

<b></b>


<b>Detalles del Pedido </b>

<b>Forma de Envio: </b> {{$formaenvio->nombre_forma_envios}}


@if($envio->costo>0)

<b>ID pedido:</b> {{ $compra->id }}<br>
<b>ID usuario masterfile:</b> {{ $compra->cod_oracle_cliente }}<br>
<b>Documento:</b> {{ 'E'.$compra->doc_cliente }}<br>
<b>Valor Pagado:</b> {{ $compra->monto_total+$envio->costo }}<br>
<b>Base Impuesto: </b>{{ number_format(($compra->base_impuesto/(1+$compra->valor_impuesto)+$envio->costo_base),0,",",".")}}<br>
<b>Valor Iva:</b> {{ $compra->monto_impuesto+$envio->costo_impuesto }}<br>
<b>Fecha de Entrega:</b> {{ $fecha_entrega }}<br>

@else

<b>ID pedido:</b> {{ $compra->id }}<br>
<b>ID usuario masterfile:</b> {{ $compra->cod_oracle_cliente }}<br>
<b>Documento:</b> {{ 'E'.$compra->doc_cliente }}<br>
<b>Valor Pagado:</b> {{ $compra->monto_total}}<br>
<b>Base Impuesto:</b> {{ $compra->base_impuesto/(1+$compra->valor_impuesto) }} <br>
<b>Valor Iva:</b> {{ $compra->monto_impuesto }}<br>
<b>Fecha de Entrega:</b> {{ $fecha_entrega }}<br>

@endif

<br>

<h3>Detalle de cada producto</h3>

<table width="100%" style="border-collapse: collapse;border: solid 2px #e9e9e9;" cellpadding="10px">
	<tr>
		<th style="border: solid 2px #e9e9e9;">Sku</th>
         <th style="border: solid 2px #e9e9e9;">Nombre Producto</th>
         <th style="border: solid 2px #e9e9e9;">Presentación Producto</th>
         <th style="border: solid 2px #e9e9e9;">Cantidad</th>
         <th style="border: solid 2px #e9e9e9;">Precio Unitario</th>
         <th style="border: solid 2px #e9e9e9;">Subtotal</th>
	</tr>

	@foreach($detalles as $row)

		<tr>
		<td style="border: solid 2px #e9e9e9;">{{$row->referencia_producto}}</td>
		<td style="border: solid 2px #e9e9e9;">{{$row->nombre_producto}}</td>
		<td style="border: solid 2px #e9e9e9;">{{$row->presentacion_producto}}</td>
        <td style="border: solid 2px #e9e9e9;"> {{ $row->cantidad }} </td>
        <td style="border: solid 2px #e9e9e9;">{{number_format($row->precio_unitario,0,",",".")}}</td>
        <td style="border: solid 2px #e9e9e9;">{{number_format($row->precio_unitario*$row->cantidad,0,",",".")}}</td>
        
	</tr>

	@endforeach

</table>


<br>
@if($envio->costo>0)

<b></b>El Costo del envio fue de <b>{{ number_format($envio->costo, 0,",",".") }}</b> <br>
El total de la compra fue de <b>{{ number_format($compra->monto_total+$envio->costo, 0,",",".") }}</b><br>
El Ahorro de su compra fue  <b>{{ number_format($compra->monto_total_base-$compra->monto_total, 0,",",".") }}</b><br>

@else

El Costo del envio fue <b>Gratis</b><br>
El total de la compra fue de <b>{{ number_format($compra->monto_total, 0,",",".") }}</b><br>
El Ahorro de su compra fue  <b>{{ number_format($compra->monto_total_base-$compra->monto_total, 0,",",".") }}</b><br>


@endif

Ip: {{$compra->ip}}


@component('mail::button', ['url' => secure_url('/admin')])
Visitar página
@endcomponent


Gracias,<br>
{{ config('app.name') }}
@endcomponent
