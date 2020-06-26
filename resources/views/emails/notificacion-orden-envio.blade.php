@component('mail::message')

Notificación de Creacion de envio en Compramas

 {{ $texto }}
<br>
  

@component('mail::button', ['url' => secure_url('/admin/ordenes')])
Ver Ordenes
@endcomponent

<h3 style="text-align: center;"><img src="{{secure_url('uploads/files/banner-750x100.jpg')}}" alt="banner"></h3>

Gracias,<br>
{{ config('app.name') }}
@endcomponent
