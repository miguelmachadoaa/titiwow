@component('mail::message')

<p style="text-align: center;"><img src="{{ url('assets/img/login.png') }}"></p>

 Bienvenido a alpina {{ $name.' '.$lastname }}

Se te ha enviado una invitacion para registrarte como afiliado por  {{ $embajador }}

Sigue el enlace para registrarte.

@component('mail::button', ['url' => url('/registroembajadores/'.$token)])
Registro 
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
