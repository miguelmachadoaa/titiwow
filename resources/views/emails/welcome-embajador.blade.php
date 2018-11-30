@component('mail::message')
Hola  {{ $name.' '.$lastname }}

¡Felicitaciones eres Embajador AlpinaGo!

Desde ahora podrás comprar tus productos favoritos y también agregar a tus amigos para que con sus compras puedas disfrutar de todos los beneficios que nuestra plataforma tiene para ti.

¡Refiere a tus amigos desde tu Área Cliente! 

@component('mail::button', ['url' => secure_url('/misamigos')])
Referir Amigos
@endcomponent

Esperamos que sigas disfrutando de la experiencia Alpina Go! Y recuerda:

¡Alpina alimenta tu vida!

<br>
{{ config('app.name') }}
@endcomponent
