@component('mail::message')

    Gracias por comprar en Alpina Go! Por tu compra has obtenido un Codigo de LifeMiles por {{$lifemile->miles}}

    <p>Codigo : {{ $lifemile->code }}</p>
   
    <br>

    Gracias,<br>
    {{  config('app.name') }}
    
@endcomponent
