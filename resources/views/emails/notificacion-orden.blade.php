@include('emails.header')

<h3>Bienvenido a Alpina Go  Cliente Cliente</h3>      

 {{ $texto }}

<br>


<p style="text-aling:center">
    <a  href="{{ secure_url('/admin/ordenes') }}" class="button button-blue " target="_blank">Ver Ordenes
</a>
</p>
  




Gracias,<br>
{{ config('app.name') }}


@include('emails.footer')
