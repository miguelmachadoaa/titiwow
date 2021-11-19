@include('emails.header')
Hola <b>{{ $compra->first_name.' '.$compra->last_name }}</b>

Finaliza la compra que tienes en proceso, te mostramos un detalle



<h3>Detalle del Carrito</h3>

<table width="100%" style="border-collapse: collapse;border: solid 2px #e9e9e9;" cellpadding="10px">
	<tr>
		<th style="border: solid 2px #e9e9e9;">Imagen</th>
		<th style="border: solid 2px #e9e9e9;">Nombre </th>
        <th style="border: solid 2px #e9e9e9;">Precio</th>
        <th style="border: solid 2px #e9e9e9;">Cantidad</th>
        <th style="border: solid 2px #e9e9e9;">SubTotal</th>
	</tr>

	@foreach($detalles as $row)

		<tr>
		<td style="border: solid 2px #e9e9e9;"><img width="60px" src="{{$configuracion->base_url.'uploads/productos/'.$row->imagen_producto}}" alt="{{$row->nombre_producto}}"></td>
		<td style="border: solid 2px #e9e9e9;">{{$row->nombre_producto}}</td>
		<td style="border: solid 2px #e9e9e9;">{{number_format($row->precio_base,0,",",".")}}</td>
		<td style="border: solid 2px #e9e9e9;">{{$row->cantidad}}</td>
        <td style="border: solid 2px #e9e9e9;">{{ number_format($row->precio_base*$row->cantidad, 0,",",".") }}</td>
	</tr>

	@endforeach

</table>
<br />




<p style="text-aling:center">
    <a  href="{{ $configuracion->base_url.'clientes/carrito/'.$compra->id }}" class="button button-blue " target="_blank">Finaliza Tu Compra </a>
</p>



Gracias,<br>
{{ config('app.name') }}
@include('emails.footer')
