@component('mail::message')

    Gracias por comprar en Alpina Go! POr tu compra has obtenido un Codigo de Llifemiles por {{$lifemile->miles}}

    <p>Codigo : {{ $lifemile->code }}</p>
   
    <br>

    Gracias,<br>
    {{  config('app.name') }}
    
@endcomponent
