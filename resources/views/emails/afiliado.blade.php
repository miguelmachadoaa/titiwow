@component('mail::message')

<p style="text-align: center;"><img src="{{ secure_url('assets/img/login.png') }}"></p>

Hola {{ $name.' '.$lastname }}, ¡LA empresa te ha agregado a su grupo Corporativo en Alpina Go!

Acepta la  invitación y regístrate para comprar tus productos favoritos.
Se te ha enviado una invitacion para registrarte como afiliado por la empresa {{ $empresa }}

¡Acepta la  invitación y regístrate ahora! 


@component('mail::button', ['url' => secure_url('/registroafiliado/'.$token)])
Registrarme
@endcomponent

Esperamos que disfrutes de la experiencia AlpinaGo! Y recuerda:

¡Alpina alimenta tu vida!<br>
{{ config('app.name') }}
@endcomponent
