@include('emails.header')
 
 Buen dia <br>

 Se adjunta archivo para descarga de reporte de formato de solicitud
<br>

Gracias,<br>
{{ config('app.name') }}
@include('emails.footer')
