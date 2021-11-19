@include('emails.header')

Gracias por su compra


{!!$producto->contenido_digial !!}

<p style="text-aling:center">
    <a  href="{{ secure_url('/clientes')}}" class="button button-blue " target="_blank">Ir a area cliente</a>
</p>


Gracias,<br>
{{ config('app.name') }}
@include('emails.footer')
