@component('mail::message')
 Bienvenido a alpina {{ $name.' '.$lastname }}

El proceso de registro ha finalizado exitosamente, ya puede comprar en AlpinaGo.

@component('mail::button', ['url' => secure_url('/')])
Visita Nuestra pagina
@endcomponent





Gracias,<br>
{{ config('app.name') }}
@endcomponent
