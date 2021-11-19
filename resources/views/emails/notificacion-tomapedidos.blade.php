@include('emails.header')

Bienvenido a Alpina Go.

Le informamos que su pedido ha sido registrado satisfactoriamente en nuestro sistema, a la espera de pago para ser procesado. 

En el siguiente enlace puede realizar el proceso de pago del mismo.

<br>
  

<p style="text-aling:center">
    <a  href="{{ secure_url('pedidos/'.$orden->token.'/pago')}}" class="button button-blue " target="_blank">Pagar Orden </a>
</p>

Si el enlace no funciona puede dirigirse a est URL {{secure_url('pedidos/'.$orden->token.'/pago')}}.


Gracias,<br>
{{ config('app.name') }}
@include('emails.footer')
