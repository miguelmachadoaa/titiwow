@include('emails.header')

 Bienvenido a Alpina Go {{ $name.' '.$lastname }}

{{ $mensaje }}

@if(isset($role->role_id))

    @if($role->role_id!='9')

	    @if ($configuracion->explicacion_precios=='1')
	

			<div  style="padding:0; margin:0;">
            
            	<a target="_blank" href="#"><img src="{{secure_url('uploads/files/banner-300x100.jpg')}}" alt="banner">
</a>
        	</div>

	    @endif

    @endif

@else

No se recibio la variable

@endif

Gracias,<br>
{{ config('app.name') }}
@include('emails.footer')
