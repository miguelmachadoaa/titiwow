@include('emails.header')

Gracias por su compra {{ $compra->first_name.' '.$compra->last_name }}



@if($compra->id_forma_envio==1)

Su compra {{ $compra->referencia }},  ya se encuentra en proceso de empaque. La misma sera entregada para la fecha  {{ $fecha_entrega }}. 

@else

Su compra {{ $compra->referencia }},  ya se encuentra en proceso de empaque. La misma sera entregada pronto. 

@endif




<h3>Detalle de compra</h3>

<table width="100%" style="border-collapse: collapse;border: solid 2px #e9e9e9;" cellpadding="10px">
	<tr>
		<th style="border: solid 2px #e9e9e9;">Producto</th>
		<th style="border: solid 2px #e9e9e9;">Presenctación</th>
         <th style="border: solid 2px #e9e9e9;">Precio</th>
         <th style="border: solid 2px #e9e9e9;">Cantidad</th>
         <th style="border: solid 2px #e9e9e9;">SubTotal</th>
	</tr>

	@foreach($detalles as $row)

		<tr>
		<td style="border: solid 2px #e9e9e9;">{{$row->nombre_producto}}</td>
		<td style="border: solid 2px #e9e9e9;">{{$row->presentacion_producto}}</td>
        <td style="border: solid 2px #e9e9e9;">{{number_format($row->precio_unitario,0,",",".")}}</td>
        <td style="border: solid 2px #e9e9e9;"> {{ $row->cantidad }} </td>
        <td style="border: solid 2px #e9e9e9;">{{ number_format($row->precio_total, 0,",",".") }}</td>
	</tr>

	@endforeach

</table>

El total de la compra fue de:  {{ number_format($compra->monto_total, 0,",",".") }} COP
Base Impuesto:  {{ number_format($compra->base_impuesto/(1+$compra->valor_impuesto), 0,",",".") }} COP
Monto Impuesto: {{ number_format($compra->monto_impuesto, 0,",",".") }} COP


Ip: {{$compra->ip}}






<p style="text-aling:center">
    <a  href="{{ secure_url('/') }}" class="button button-blue " target="_blank">Sigue Comprando </a>
</p>



Gracias,<br>
{{ config('app.name') }}
@include('emails.footer')
