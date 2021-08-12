@component('mail::message')

Bienvenido a Alpina Go.

Le informamos que su pedido ha sido registrado satisfactoriamente en nuestro sistema, a la espera de pago para ser procesado. 

En el siguiente enlace puede realizar el proceso de pago del mismo.


<br>
  

@component('mail::button', ['url' => secure_url('pedidos/'.$orden->token.'/pago')])
Pagar Orden
@endcomponent

Si el enlace no funciona puede dirigirse a est URL {{secure_url('pedidos/'.$orden->token.'/pago')}}.


Gracias,<br>
{{ config('app.name') }}
@endcomponent
