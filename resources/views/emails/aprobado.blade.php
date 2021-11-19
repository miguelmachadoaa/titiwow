@include('emails.header')
 Bienvenido a alpina {{ $name.' '.$lastname }}

El proceso de registro ha finalizado exitosamente, desde ahora puedes comprar en Alpina Go!.

<p style="text-aling:center">
    <a  href="{{ secure_url('/') }}" class="button button-blue " target="_blank">Visitar Página </a>
</p>


Gracias,<br>
{{ config('app.name') }}
@include('emails.footer')
