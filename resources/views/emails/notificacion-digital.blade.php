@component('mail::message')

Gracias por su compra


{!!$producto->contenido_digial !!}

  

@component('mail::button', ['url' => secure_url('/clientes')])
Ir a area cliente
@endcomponent


Gracias,<br>
{{ config('app.name') }}
@endcomponent
