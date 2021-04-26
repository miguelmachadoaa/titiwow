@component('mail::message')

Gracias por su compra


{!!$producto->contenido_digial !!}

  

@component('mail::button', ['url' => secure_url('/clientes')])
Ir a area cliente
@endcomponent

<h3 style="text-align: center;"><img src="{{secure_url('uploads/files/banner-750x100.jpg')}}" alt="banner"></h3>

Gracias,<br>
{{ config('app.name') }}
@endcomponent
