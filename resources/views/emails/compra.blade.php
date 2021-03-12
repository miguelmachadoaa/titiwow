@component('mail::message')

Gracias por su compra <b>{{ $compra->first_name.' '.$compra->last_name }}</b>

@if($compra->id_forma_envio==1)

Hemos registrado una compra {{ $compra->referencia }},  Ha seleccionado enviar el pedido con <b>{{ $compra->nombre_forma_envios }}</b>. Si quieres saber el estatus de tu pedido comunicate a la linea (+571)4238600 y al correo contaccenter@alpina.com 

@else

Hemos registrado una compra {{ $compra->referencia }},  Ha seleccionado enviar el pedido con <b>{{ $compra->nombre_forma_envios }}</b> y será entregado pronto. 


@endif

Datos de la compra


@if($envio->costo>0)


	IdPedido: {{ $compra->id }}
	Documento: {{ $compra->doc_cliente }}
	Valor Pagado: {{ number_format($compra->monto_total+$envio->costo,0,",",".") }}
	Base Impuesto: {{ number_format($compra->base_impuesto+$envio->costo_base,0,",",".")}}
	Valor Iva: {{ number_format($compra->monto_impuesto+$envio->costo_impuesto,0,",",".")}}
	Si quieres saber el estatus de tu pedido comunicate a la linea (+571)4238600 y al correo contaccenter@alpina.com

@else


	IdPedido: {{ $compra->id }}
	Documento: {{ $compra->doc_cliente }}
	Valor Pagado: {{ number_format($compra->monto_total,0,",",".") }}
	Base Impuesto: {{ number_format($compra->base_impuesto,0,",",".")}}
	Valor Iva: {{ number_format($compra->monto_impuesto,0,",",".")}}
	Si quieres saber el estatus de tu pedido comunicate a la linea (+571)4238600 y al correo contaccenter@alpina.com

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

El Costo del envio fue de {{ number_format($envio->costo, 0,",",".") }}
El total de la compra fue de {{ number_format($compra->monto_total+$envio->costo, 0,",",".") }}

	@if($role->oferta==0)
		El Ahorro de su compra fue  {{ number_format($compra->monto_total_base-$compra->monto_total, 0,",",".") }}
	@endif
@else

El Costo del envio fue Gratis
El total de la compra fue de {{ number_format($compra->monto_total, 0,",",".") }}

	@if($role->oferta==0)
		El Ahorro de su compra fue  {{ number_format($compra->monto_total_base-$compra->monto_total, 0,",",".") }}
	@endif

@endif


@if(is_null($compra->token))

@else

	




		<table class="action" align="center" width="100%" cellpadding="0" cellspacing="0">
<tr>
    <td align="center">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center">
                    <table border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td>
                                <a href="{{secure_url('/tracking/'.$compra->token)}}" class="button button-blue" target="_blank">Sigue tu envio</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>

</table>


@endif


Gracias,<br>
{{ config('app.name') }}
@endcomponent
