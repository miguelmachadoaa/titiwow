@include('emails.header')

<p>Gracias por registrarte en AlpinaGo</p>

<p>Por favor Confirma tu correo siguiendo el siguiente enlace o dirigiéndote a la Dirección Web que le está más abajo</p>

<p style="text-aling:center">
	<a  href="{{ secure_url('/confirmarcorreo/'.$user->token) }}" class="button button-blue " target="_blank">Confirmar Correo</a>
</p>  

<p>Si su navegador no lo redirige por favor diríjase a esta url</p>

<p>
	{{secure_url('/confirmarcorreo/'.$user->token)}}
</p>


<p>Gracias,</p><br>
{{ config('app.name') }}
@include('emails.footer')
