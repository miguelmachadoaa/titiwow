@component('mail::message')


 
 Buen dia <br>

 Se adjunta archivo para descarga de reporte de ventas para la gestion de Ultima Milla para el dia {{ $fecha }}.



Gracias,<br>
{{ config('app.name') }}
@endcomponent
