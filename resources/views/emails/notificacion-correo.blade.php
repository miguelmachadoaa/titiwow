@component('mail::message')

Gracias por registrarte en alpinaGo

Por favor Confirma tu correo siguiendo el siguiente enlace o dirigiéndote a la Dirección Web que le está más abajo

  

@component('mail::button', ['url' => secure_url('/confirmarcorreo/'.$user->token)])
Confirmar Correo 
@endcomponent

Si su navegador no lo redirige por favor diríjase a esta url

<p>
	{{secure_url('/confirmarcorreo/'.$user->token)}}
</p>


Gracias,<br>
{{ config('app.name') }}
@endcomponent
