@include('emails.header')

<p>Se ha registrado un pedido por el usuario <b>{{ $compra->first_name.' '.$compra->last_name }}</b> </p>


<p><b>Datos Del Cliente</b></p>


<p><b>Nombre: </b> {{ $compra->first_name.' '.$compra->last_name }}</p><br>

<p><b>Documento: </b> {{ $cliente->doc_cliente }}</p><br>

<p><b>Teléfono</b> {{$cliente->telefono_cliente}}</p><br>

<p><b>Direccion de Envio</b></p>

<p>{{ $direccion->titulo   }} </p>
<p>{{ $direccion->state_name.' , '.$direccion->city_name   }}</p>
<p>{{ $direccion->nombre_estructura.' '.$direccion->principal_address .' #'. $direccion->secundaria_address .'-'.$direccion->edificio_address.', '.$direccion->detalle_address.', '.$direccion->barrio_address }}</p>
<p>{{ $direccion->notas }}</p>


<p><b>Detalles del Pedido </b></p>

<p><b>Forma de Envio: </b> {{$formaenvio->nombre_forma_envios}}</p>

@if($compra->estatus_pago==1)

<p><h3>Este pedido aún no ha sido pagado, debes esperar la confirmación</h3></p>


@elseif($compra->estatus_pago==2)

<p><h3>Este pedido ya ha sido pagado.</h3></p>

@elseif($compra->estatus_pago==3)

<p><h3>El pago del pedido ha sido cancelado, Esperar un nuevo pago </h3></p>

@elseif($compra->estatus_pago==4)

<p><h3>Este pedido aún no ha sido pagado, debes esperar la confirmación</h3></p>

@endif



@if($envio->costo>0)

<p><b>ID pedido:</b> {{ $compra->id }}</p><br>
<p><b>ID usuario masterfile:</b> {{ $compra->cod_oracle_cliente }}<p><br>
<p><b>Documento:</b> {{ 'E'.$compra->doc_cliente }}</p><br>
<p><b>Valor Pagado:</b> {{ $compra->monto_total+$envio->costo }}</p><br>
<p><b>Base Impuesto: </b>{{ number_format(($compra->base_impuesto/(1+$compra->valor_impuesto)+$envio->costo_base),0,",",".")}}<p><br>
<p><b>Valor Iva:</b> {{ $compra->monto_impuesto+$envio->costo_impuesto }}</p><br>


@else

<p><b>ID pedido:</b> {{ $compra->id }}</p><br>
<p><b>ID usuario masterfile:</b> {{ $compra->cod_oracle_cliente }}</p><br>
<p><b>Documento:</b> {{ 'E'.$compra->doc_cliente }}</p><br>
<p><b>Valor Pagado:</b> {{ $compra->monto_total}}</p><br>
<p><b>Base Impuesto:</b> {{ $compra->base_impuesto/(1+$compra->valor_impuesto) }} </p><br>
<p><b>Valor Iva:</b> {{ $compra->monto_impuesto }}</p><br>

@endif

<br>

<p><h3>Detalle de cada producto</h3></p>

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

	@if(is_null($row->deleted_at))

		<tr>
		<td style="border: solid 2px #e9e9e9;">{{$row->referencia_producto}}</td>
		<td style="border: solid 2px #e9e9e9;">{{$row->nombre_producto}}</td>
		<td style="border: solid 2px #e9e9e9;">{{$row->presentacion_producto}}</td>
        <td style="border: solid 2px #e9e9e9;"> {{ $row->cantidad }} </td>
        <td style="border: solid 2px #e9e9e9;">{{number_format($row->precio_unitario,0,",",".")}}</td>
        <td style="border: solid 2px #e9e9e9;">{{number_format($row->precio_unitario*$row->cantidad,0,",",".")}}</td>
        
	</tr>

	@endif

	@endforeach

</table>


<br>
@if($envio->costo>0)

<p>El Costo del envio fue de <b>{{ number_format($envio->costo, 0,",",".") }}</b> <br>
El total de la compra fue de <b>{{ number_format($compra->monto_total+$envio->costo, 0,",",".") }}</b><br>
El Ahorro de su compra fue  <b>{{ number_format($compra->monto_total_base-$compra->monto_total, 0,",",".") }}</b><br></p>

@else

<p>El Costo del envio fue <b>Gratis</b><br>
El total de la compra fue de <b>{{ number_format($compra->monto_total, 0,",",".") }}</b><br>
El Ahorro de su compra fue  <b>{{ number_format($compra->monto_total_base-$compra->monto_total, 0,",",".") }}</b><br></p>


@endif

<p>Ip: {{$compra->ip}}</p>

<p style="text-aling:center">
    <a  href="{{ secure_url(/admin) }}" class="button button-blue " target="_blank">Visitar Página Admin</a>
</p>


<p>Gracias,</p><br>
{{ config('app.name') }}
@include('emails.footer')
