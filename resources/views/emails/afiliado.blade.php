@component('mail::message')

<p style="text-align: center;"><img src="{{ secure_url('assets/img/login.png') }}"></p>

 Bienvenido a Alpina GO! {{ $name.' '.$lastname }}

Se te ha enviado una invitacion para registrarte como afiliado por la empresa {{ $empresa }}

Sigue el enlace para registrarte.

@component('mail::button', ['url' => secure_url('/registroafiliado/'.$token)])
Registro 
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
