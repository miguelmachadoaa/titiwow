@component('mail::message')
 Bienvenido a alpina {{ $name.' '.$lastname }}

Bienvenido a Alpina

@component('mail::button', ['url' => '/'])
Vista nuestro sitio
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
