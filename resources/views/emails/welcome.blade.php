@component('mail::message')
 Bienvenido a Alpina Go {{ $name.' '.$lastname }}

Estamos procesando tu solicitud de registro, te notificaremos una vez haya finalizado el proceso, este proceso puede tomar hasta 24 horas hábiles.



Gracias,<br>
{{ config('app.name') }}
@endcomponent
