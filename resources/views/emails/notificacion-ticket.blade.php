@component('mail::message')

Se ha creado un nuevo ticket 

<p>Asunto : {{$ticket->titulo_ticket }}</p>
<p>Contenido : {{$ticket->texto_ticket }}</p>
<p>Fecha de Creación : {{$ticket->created_at }}</p>
<br>
  

@component('mail::button', ['url' => secure_url('/admin/ticket')])
Ir a mesa de soporte
@endcomponent

<h3 style="text-align: center;"><img src="{{secure_url('uploads/files/banner-750x100.jpg')}}" alt="banner"></h3>

Gracias,<br>
{{ config('app.name') }}
@endcomponent
