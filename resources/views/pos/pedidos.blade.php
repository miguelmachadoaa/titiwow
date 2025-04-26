<div class="row">

    <div class="col-sm-12 " style="margin-top: 1em;">
        <div class="row">

            <div class="col-sm-2">
                <a data-id="dashboard" class=" btn btn-primary cajita" href="#"><i class="fa fa-home"></i></a>
            </div>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="terminopedido" id="terminopedido" value=""
                    placeholder="Buscar">
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-primary buscarpedido"><i class="fa fa-search"></i></button>

            </div>
        </div>

    </div>

    <div class="col-sm-12">

        @php

            $total_bs = 0;
            $total_usd = 0;
            $i = 0;


        @endphp

        @foreach ($ordenes as $o)

            @php
                $i++;
                $total_bs += $o->monto_total;
                $total_usd += $o->monto_total / $o->tasa_dolar;
            @endphp

            <div class="row cajasombra mt-2 pedidos" data-json="{{ json_encode($o) }}">

                <div class="col-sm-2">
                    {{ $i}}</div>
                <div class="col-sm-2">{{ $o->cliente->first_name . ' ' . $o->cliente->last_name }}</div>
                <div class="col-sm-2">{{ $o->created_at }}</div>
                <div class="col-sm-2">
                    <p style="margin: 0; padding:0;">Bs. {{ $o->monto_total }}</p>
                    <p style="margin: 0; padding:0;">Usd. {{ $o->monto_total / $o->tasa_dolar }}</p>
                </div>
                <div class="col-sm-3">
                    @foreach ($o->detalles as $d)
                        <p style="padding: 0; margin:0">{{ $d->cantidad }} - {{ $d->producto->nombre_producto }}</p>
                    @endforeach
                </div>
                <div class="col-sm-1">
                    <button type="button" data-id="{{ $o->id }}" class="btn btn-primary detalleorden"><i
                            class="fa fa-eye"></i></button>
                </div>

            </div>
        @endforeach

            
        <div class="row cajasombra mt-2 pedidos" data-json="#">

            <div class="col-sm-2">    </div>
            <div class="col-sm-2"> </div>
            <div class="col-sm-2">Totales </div>
            <div class="col-sm-2">
                <p style="margin: 0; padding:0;">Bs. {{ $total_bs}}</p>
                <p style="margin: 0; padding:0;">Usd. {{ $total_usd }}</p>
            </div>
            <div class="col-sm-3">
                
            </div>
            <div class="col-sm-1">
                
            </div>

        </div>

    </div>


</div>
