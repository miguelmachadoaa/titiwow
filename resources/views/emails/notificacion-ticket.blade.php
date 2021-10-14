@include('emails.header')

            <h3>Se ha creado un nuevo ticket </h3>
            
            <p>Asunto : {{ $ticket->titulo_ticket }}</p>
            <p>Contenido : {!! $ticket->texto_ticket !!}</p>
            <p>Fecha de Creación : {{ $ticket->created_at }}</p>


            @if(isset($ticket->comentarios))

                @if(count($ticket->comentarios))

                    <h3>Respuestas</h3>

                    @foreach($ticket->comentarios as $c)

                        <p>Usuario: {{ $c->first_name.' '.$c->last_name}}</p>

                        <p>{{ $c->texto_ticket}}</p>
                        
                            @if(is_null($c->archivo) || $c->archivo=='')

                            <p>Sin archivos Adjuntos </p>

                            @else

                            <p>Adjunto <a class="btn btn-info" target="_blank" href="{{ secure_url('uploads/ticket/'.$c->archivo)}}">Ver Archivo </a> </p>
                            
                            @endif 
                        

                    @endforeach

                @endif

            @endif

            <p style="text-aling:center">
            <a  href="{{ secure_url('/admin/ticket') }}" class="button button-blue " target="_blank">Ir a mesa de soporte</a>

            </p>

            <br>
    Gracias,<br>
    {{  config('app.name') }}

    @include('emails.footer')
    

