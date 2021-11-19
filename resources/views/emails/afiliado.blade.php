@include('emails.header')

<p style="text-align: center;"><img src="{{ secure_url('assets/img/login.png') }}"></p>




Hola {{ $name.' '.$lastname }}, has sido invitado a registrarse en AlpinaGO.com por ser parte de {{ $empresa }}.

Regístrate con el sigueinte link para aceptar la invitación y poder comprar los productos de Alpina a  precios especiales.

AlpinaGO es el portal exclusivo de comercio electrónico de Alpina. Recuerda que todas tus compras son con envío gratis.




<p style="text-aling:center">
    <a  href="{{ secure_url('/registroafiliado/'.$token) }}" class="button button-blue " target="_blank">Registrarme</a>
</p>


Esperamos que disfrutes de la experiencia AlpinaGo! Y recuerda:

¡Alpina alimenta tu vida!<br>
{{ config('app.name') }}
@include('emails.footer')
