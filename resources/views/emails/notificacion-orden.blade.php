@component('mail::message')

<p style="text-align: center;"><img src="{{ url('assets/img/login.png') }}"></p>

 {{ $texto }}
<br>
 

@component('mail::button', ['url' => url('/admin/ordenes')])
Ver Ordenes
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
