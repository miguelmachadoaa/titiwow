@component('mail::message')

Bienvenido a Alpina Go  Cliente Cliente

 Gracias por su compra
<br>
  

@component('mail::button', ['url' => secure_url('/admin/ordenes')])
Ver Ordenes
@endcomponent


Gracias,<br>
{{ config('app.name') }}
@endcomponent
