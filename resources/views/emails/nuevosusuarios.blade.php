@component('mail::message')

<!--<p style="text-align: center;"><img src="{{ secure_url('assets/img/login.png') }}"></p>-->

 
 Buen dia <br>

 Se adjunta archivo para descarga de reporte usuarios para ser activados para el dia {{ $fecha }} para descargar el archivo hacer click en el siguiente enlace

<br>
 

@component('mail::button', ['url' => $enlace])
Descargar Archivo
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
