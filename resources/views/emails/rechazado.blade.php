@component('mail::message')
 
Bienvenido a alpina 

El usuario {{ $name.' '.$lastname }} ha sido rechazado por masterfile, el motivo es el siguiente 

{{ $motivo }}

Se agredece tomar las consideraciones en el caso.




Gracias,<br>
{{ config('app.name') }}
@endcomponent
