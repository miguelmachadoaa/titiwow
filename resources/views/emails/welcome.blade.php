@component('mail::message')
 Bienvenido a Alpina Go {{ $name.' '.$lastname }}



{{ $mensaje }}


Gracias,<br>
{{ config('app.name') }}
@endcomponent
