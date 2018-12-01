@component('mail::message')

<h3><b>Hola {{ $name.' '.$lastname }}</b></h3>

<p class="text-aling:center;">¡Nuestro embajador {{ $embajador }} te ha agregado a su grupo de amigos en AlpinaGo! </p>

<p>Acepta la  invitación y regístrate, Compra tus productos favoritos y agrega a tus amigos para que con sus compras puedas disfrutar de todos los beneficios que nuestra plataforma tiene para ti.
</p>

<p>¡Acepta la  invitación y regístrate ahora! </p>

@component('mail::button', ['url' => secure_url('/registroembajadores/'.$token)])
Registrarme
@endcomponent

Esperamos que disfrutes de la experiencia AlpinaGo! Y recuerda:

¡Alpina alimenta tu vida!<br>
{{ config('app.name') }}
@endcomponent
