@include('emails.header')

<p>Gracias por comprar en Alpina Go! Por tu compra has obtenido un Codigo de LifeMiles por <b>{{$lifemile->miles}}</b> </p>

<p>Codigo : <b>{{ $lifemile->code }}</b></p>

<br>

Gracias,<br>
{{  config('app.name') }}

@include('emails.footer')