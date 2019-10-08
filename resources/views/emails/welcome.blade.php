@component('mail::message')
 Bienvenido a Alpina Go {{ $name.' '.$lastname }}



{{ $mensaje }}


@if(isset($role->role_id))

    @if($role->role_id!='9')

        <div class="col-sm-12" style="padding:0; margin:0;">
            
            <img src="{{secure_url('uploads/files/banner-300x100.jpg')}}" alt="banner">

        </div>

    @endif

@else

No se recibio la variable

@endif


Gracias,<br>
{{ config('app.name') }}
@endcomponent
