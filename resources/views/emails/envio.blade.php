@include('emails.header')


<p>Hola <b>{{ $user->first_name.' '.$user->last_name }}</b></p>

<p>
@switch($status->id)
    @case(1)

        <p>El envio de su orden {{$orden->referecia}}, ha sido Recibido.</p>
        @break

    @case(2)

        <p>El envio de su orden {{$orden->referecia}}, Esta en transito.</p>
    @break

    @case(3)

    <p>El envio de su orden {{$orden->referecia}}, Ha sido entregado.</p>
    @break

    @case(4)

    <p>El envio de su orden {{$orden->referecia}}, Esta en proceso de empaquetado.</p>
    @break

    @case(5)

    <p>El envio de su orden {{$orden->referecia}}, Ha sido asignado para entrega.</p>
    @break

    @case(6)

    <p>El envio de su orden {{$orden->referecia}}, Esta ruta de entrega.</p>
    @break

    @case(7)

    <p>El envio de su orden {{$orden->referecia}}, Ha sido entregado.</p>
    @break

    @case(8)

    <p>El envio de su orden {{$orden->referecia}}, Esta en proceso de cancelación.</p>
    @break

    @case(9)
    
    <p>El envio de su orden {{$orden->referecia}}, Ha sido cancelado.</p>
    @break


    @default
        
@endswitch
</p>

<p>Puedes ver el detalle de tu compra y  Rastrearla en la seccion de mis compras en tu area de cliente.</p>

<p style="text-aling:center">
    <a  href="{{ secure_url('/clientes') }}" class="button button-blue " target="_blank">Ir a Area de Cliente.</a>
</p>

<p>Gracias,</p><br>
{{ config('app.name') }}
@include('emails.footer')
