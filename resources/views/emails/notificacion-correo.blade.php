@component('mail::message')

Gracias por registrarte en alpinaGo

Por favor Confirma tu correo siguendo el siguiente enlace o dirigiendote a la Dirección Web que le esta mas abajo

  

@component('mail::button', ['url' => secure_url('/confirmarcorreo/'.$user->token)])
Confirmar Correo 
@endcomponent

Si su navegador no lo redigire por favor dirijase a esta url 

<p>
	{{secure_url('/confirmarcorreo/'.$user->token)}}
</p>

<h3 style="text-align: center;"><img src="{{secure_url('uploads/files/banner-750x100.jpg')}}" alt="banner"></h3>

Gracias,<br>
{{ config('app.name') }}
@endcomponent
