@component('mail::message')

<p style="text-align: center;"><img src="{{ url('assets/img/logo_alpina.png') }}"></p>

 {{ $texto }}
<br>
 {{ $orden }}

@component('mail::button', ['url' => url('#')])
Registro 
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
