@component('mail::message')
 Bienvenido a alpina {{ $name.' '.$lastname }}

Te hemos registrado en nuestro sistema como embajador AlpniGo, por lo que podras agregar amigos y ganar comisiones por tus compras y las de ellos.

Puedes Referir amigos en tu area cliente y ver las compras que ellos hagan

Comienza ya 


@component('mail::button', ['url' => secure_url('/')])
Visita Nuestra pagina
@endcomponent


Gracias,<br>
{{ config('app.name') }}
@endcomponent
