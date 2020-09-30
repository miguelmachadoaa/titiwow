@component('mail::message')

Bienvenido a Alpina Go.

Le informamos que su pedido ha sido registrado satisfacoriamente en nuestro sistema, a la espera de pago para ser procesado. 

En el siguiente enlace puede realizar el proceso de pago del mismo.


<br>
  

@component('mail::button', ['url' => secure_url('pedidos/'.$orden->token.'/pago')])
Pagar Orden
@endcomponent

Si el enlace no funciona puede dirigirse a est url {{secure_url('pedidos/'.$orden->token.'/pago')}}.

<h3 style="text-align: center;"><img src="{{secure_url('uploads/files/banner-750x100.jpg')}}" alt="banner"></h3>

Gracias,<br>
{{ config('app.name') }}
@endcomponent
