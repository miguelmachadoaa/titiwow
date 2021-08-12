@component('mail::message')

Bienvenido a Alpina Go  Cliente Cliente

 {{ $texto }}
<br>
  

@component('mail::button', ['url' => secure_url('/admin/ordenes')])
Ver Ordenes
@endcomponent


Gracias,<br>
{{ config('app.name') }}
@endcomponent
