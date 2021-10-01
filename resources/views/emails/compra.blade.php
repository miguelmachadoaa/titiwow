@include('emails.header')


<p>Gracias por su compra <b>{{ $compra->first_name.' '.$compra->last_name }}</b></p>
@if($compra->id_forma_envio==1)


<p>Hemos registrado una compra {{ $compra->referencia }},  Ha seleccionado enviar el pedido con <b>{{ $compra->nombre_forma_envios }}</b>.</p>
@else


<p>Hemos registrado una compra {{ $compra->referencia }},  Ha seleccionado enviar el pedido con <b>{{ $compra->nombre_forma_envios }}</b> y será entregado pronto. </p>

@endif
<p>Datos de la compra</p>



@if($envio->costo>0)
<p>IdPedido: {{ $compra->id }}</p>
<p>Documento: {{ $compra->doc_cliente }}</p>
<p>Valor Pagado: {{ number_format($compra->monto_total+$envio->costo,0,",",".") }}</p>
<p>Base Impuesto: {{ number_format($compra->base_impuesto+$envio->costo_base,0,",",".")}}</p>	
<p>Valor Iva: {{ number_format($compra->monto_impuesto+$envio->costo_impuesto,0,",",".")}}</p>	
	
	

@else
<p>IdPedido: {{ $compra->id }}</p>
<p>Documento: {{ $compra->doc_cliente }}</p>
<p>Valor Pagado: {{ number_format($compra->monto_total,0,",",".") }}</p>	
<p>Base Impuesto: {{ number_format($compra->base_impuesto,0,",",".")}}</p>
<p>Valor Iva: {{ number_format($compra->monto_impuesto,0,",",".")}}</p>	
	
	

@endif


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

	@if(is_null($row->deleted_at))


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

	@endif

	@endforeach

</table>
<br />


@if($envio->costo>0)
<p>El Costo del envio fue de {{ number_format($envio->costo, 0,",",".") }}</p>
<p>El total de la compra fue de {{ number_format($compra->monto_total+$envio->costo, 0,",",".") }}</p>


	@if($role->oferta==0)
	<p>El Ahorro de su compra fue  {{ number_format($compra->monto_total_base-$compra->monto_total, 0,",",".") }}</p>
		
	@endif
@else

<p>El Costo del envio fue Gratis</p>
<p>El total de la compra fue de {{ number_format($compra->monto_total, 0,",",".") }}</p>



	@if($role->oferta==0)
	<p>El Ahorro de su compra fue  {{ number_format($compra->monto_total_base-$compra->monto_total, 0,",",".") }}</p>
		
	@endif

@endif


@if(is_null($compra->token))

@else

<p style="text-aling:center">
<a  href="{{ secure_url('/tracking/'.$compra->token) }}" class="button button-blue " target="_blank">Rastrea tu Pedido</a>

</p>



@endif


Gracias,<br>
{{ config('app.name') }}
@include('emails.footer')
