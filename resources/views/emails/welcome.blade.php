@component('mail::message')
# Bienvenido a alpina {{ $name }}

Bienvenido a Alpina

@component('mail::button', ['url' => '/'])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
