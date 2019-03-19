@component('mail::message')

<h3><b>Hola {{ $name.' '.$lastname }}</b></h3>

<p class="text-aling:center;">¡Nuestro embajador {{ $embajador }} te ha agregado a su grupo de amigos en AlpinaGo! </p>

<p>Aceptando esta invitación y registrándote podrás comprar tus productos favoritos a precios exclusivos y recibirlos SIN COSTO en la puerta de tu casa.
</p>

<p>¿Qué esperas? Regístrate! </p>

@component('mail::button', ['url' => secure_url('/registroembajadores/'.$token)])
Registrarme
@endcomponent

Esperamos que disfrutes de la experiencia AlpinaGo! Y recuerda:

Alpina alimenta tu vida<br>
{{ config('app.name') }}
@endcomponent
