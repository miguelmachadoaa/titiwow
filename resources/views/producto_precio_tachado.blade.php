@if($almacen->descuento_productos=='1')

    @if($producto->mostrar_descuento=='1')

        @if(isset($producto->mostrar))

            @if($producto->mostrar==1)

                @if($producto->precio_base>$producto->precio_oferta)

                    <del class="">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;

                @endif


            @endif

        @endif


    @endif


@endif