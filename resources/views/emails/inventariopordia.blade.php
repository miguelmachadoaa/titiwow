@component('mail::message')


 
 Buen dia <br>

 Se adjunta archivo para descarga de reporte de inventario para el dia {{ $fecha }} del almacen {{$almacen}} para descargar el archivo hacer click en el siguiente enlace

<br>
 

@component('mail::button', ['url' => $enlace])
Descargar Archivo
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
