@include('emails.header')

 <h3><b>Hola Embajador.</b></h3>
 
 <h3><b>Tu amigo {{ $name.' '.$lastname }} ha completado su registro en Alpina Go!</b></h3>

<p>Por cada amigo que registres tendrás acceso a beneficios y premios exclusivos dentro de nuestra plataforma.</p>

Puedes ver el status de cada uno de tus referidos entrando a tu perfil:

<p style="text-aling:center">
    <a  href="{{ secure_url('/') }}" class="button button-blue " target="_blank">Visitar Página </a>
</p>


<p>Gracias,</p><br>
{{ config('app.name') }}
@include('emails.footer')