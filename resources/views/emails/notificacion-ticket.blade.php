@component('mail::message')

Se ha creado un nuevo ticket 

<p>Asunto : {{$ticket->titulo_ticket }}</p>
<p>Contenido : {!!$ticket->texto_ticket !!}</p>
<p>Fecha de Creación : {{$ticket->created_at }}</p>
<br>

@if(isset($ticket->comentarios))

    @if(count($ticket->comentarios))

        <h3>Respuestas</h3>

        @foreach($ticket->comentarios as $c)

            <p>Usuario: {!!$c->first_name.' '.$c->last_name!!}</p>
            <p>{!!$c->texto_ticket!!}</p>
            <p>
                @if(is_null($c->archivo) || $c->archivo=='')

                    Sin archivos Adjuntos 

                @else

                    Adjunto <a class="btn btn-info" target="_blank" href="{!!secure_url('uploads/ticket/'.$c->archivo)!!}">Ver Archivo </a> </h3>
                
                @endif 
            </p>

        @endforeach

    @endif

@endif
  

@component('mail::button', ['url' => secure_url('/admin/ticket')])
Ir a mesa de soporte
@endcomponent


Gracias,<br>
{{ config('app.name') }}
@endcomponent
