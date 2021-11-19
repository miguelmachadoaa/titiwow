@include('emails.header')

<!--<p style="text-align: center;"><img src="{{ secure_url('assets/img/login.png') }}"></p>-->

 
 Buen dia <br>

 Se adjunta archivo para descarga de reporte de toma de pedidos para la fecha  {{ $fecha }} para descargar el archivo hacer click en el siguiente enlace

<br>
 



<p style="text-aling:center">
    <a  href="{{ $enlace }}" class="button button-blue " target="_blank">Descargar Archivo </a>
</p>


Gracias,<br>
{{ config('app.name') }}
@include('emails.footer')
