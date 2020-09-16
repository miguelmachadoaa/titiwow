@component('mail::message')


 
 Buen dia <br>

 Se adjunta archivo para descarga de reporte de ventas para la gestion de Ultima Milla para el dia {{ $fecha }} para descargar el archivo hacer click en el siguiente enlace

<br>
 

@component('mail::button', ['url' => $enlace])
Descargar Archivo
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
