@component('mail::message')

<!--<p style="text-align: center;"><img src="{{ secure_url('assets/img/login.png') }}"></p>-->

 {{ $texto }}
<br>
 

@component('mail::button', ['url' => secure_url('/admin/ordenes')])
Ver Ordenes
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
