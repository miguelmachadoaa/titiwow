@component('mail::message')
 Bienvenido a alpina {{ $name.' '.$lastname }}

El proceso de registro ha finalizado exitosamente, desde ahora puedes comprar en Alpina Go!.

@component('mail::button', ['url' => secure_url('/')])
Visita Nuestra Tienda
@endcomponent


Gracias,<br>
{{ config('app.name') }}
@endcomponent
