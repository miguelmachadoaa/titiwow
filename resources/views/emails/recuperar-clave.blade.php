@include('emails.header')

<p>Hola! <b>{{ $data['user_name'] }}</b></p>

<p>Se ha solicitado un enlace para cambiar su contraseña. Puede hacerlo a través del siguiente botón</p>


<p style="text-aling:center">
    <a  href="{{ secure_url($data['forgotPasswordUrl']) }}" class="button button-blue " target="_blank">Restablecer Contraseña</a>
</p>

<p>Si no ha solicitado esto, ignore este correo electrónico. Su contraseña no cambiará hasta que acceda al enlace anterior y cree una nueva.</p>



<p>Gracias,</p><br>
{{ config('app.name') }}
@include('emails.footer')
