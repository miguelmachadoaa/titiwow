@component('mail::message')


Hola {{ $user->first_name.' '.$user->last_name }}


@switch($status->id)
    @case(1)

        El envio de su orden {{$orden->referecia}}, ha sido Recibido.
        @break

    @case(2)

    El envio de su orden {{$orden->referecia}}, Esta en transito.
    @break

    @case(3)

    El envio de su orden {{$orden->referecia}}, Ha sido entregado.
    @break

    @case(4)

    El envio de su orden {{$orden->referecia}}, Esta en proceso de empaquetado.
    @break

    @case(5)

    El envio de su orden {{$orden->referecia}}, Ha sido asignado para entrega.
    @break

    @case(6)

    El envio de su orden {{$orden->referecia}}, Esta ruta de entrega
    @break

    @case(7)

    El envio de su orden {{$orden->referecia}}, Ha sido entregado.
    @break

    @case(8)

    El envio de su orden {{$orden->referecia}}, Esta en proceso de cancelación.
    @break

    @case(9)
    
    El envio de su orden {{$orden->referecia}}, Ha sido cancelado.
    @break


    @default
        
@endswitch

Puedes ver el detalle de tu compra y  Rastrearla en la seccion de mis compras en tu area de cliente.


@component('mail::button', ['url' => secure_url('/clientes')])
Ir a Area de Cliente.
@endcomponent


Gracias,<br>
{{ config('app.name') }}
@endcomponent
