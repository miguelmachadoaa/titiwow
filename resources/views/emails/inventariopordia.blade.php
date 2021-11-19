@include('emails.header')


 
 Buen dia <br>

 Se adjunta archivo para descarga de reporte de inventario para el dia {{ $fecha }} del almacen {{$almacen}} para descargar el archivo hacer click en el siguiente enlace

<br>
 

<p style="text-aling:center">
    <a  href="{{ $enlace}}" class="button button-blue " target="_blank">Descargar Archivo </a>
</p>



Gracias,<br>
{{ config('app.name') }}
@include('emails.footer')