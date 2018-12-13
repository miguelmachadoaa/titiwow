@component('mail::message')

 <h3><b>Hola {{ $name.' '.$lastname }}.</b></h3>
 
 <h3><b>Tu amigo ha completado su registro en Alpina Go!</b></h3>

<p>Por cada amigo que registres tendrás acceso a beneficios y premios exclusivos dentro de nuestra plataforma.</p>

Puedes ver el status de cada uno de tus referidos entrando a tu perfil:

@component('mail::button', ['url' => secure_url('/')])
Ir a tu Perfil
@endcomponent

¡Alpina alimenta tu vida!<br>
{{ config('app.name') }}
@endcomponent
