@component('mail::message')

<!--<p style="text-align: center;"><img src="{{ secure_url('assets/img/login.png') }}"></p>-->

 <h3><b>Bienvenido a alpina {{ $name.' '.$lastname }}</b></h3>

<p>Se te ha enviado una invitacion para registrarte como afiliado por </p>

<p>{{ $embajador }}</p> 

<p>Sigue el enlace para registrarte.</p>



@component('mail::button', ['url' => secure_url('/registroembajadores/'.$token)])
Registro 
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
