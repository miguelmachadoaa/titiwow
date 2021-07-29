@if($i<12)

    @if($producto->tipo_producto=='1' || $producto->tipo_producto=='3')

        @if(isset($inventario[$producto->id]))

            @if($inventario[$producto->id]>0)

            @php $i++; @endphp

                <div class="col-md-2 col-sm-6 col-xs-6 ">
                
                    <div class="productos">
                
                        <div class="text-align:center;">
                    
                            <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ secure_url('/').'/uploads/productos/250/'.$producto->imagen_producto }}" alt="{{ $producto->nombre_producto }}" title="{{ $producto->nombre_producto }}" class="img-responsive homi"></a>

                            @if(isset($inventario[$producto->id]))
                            
                                @if($inventario[$producto->id]<=0)

                                    <img class="agotado" style="" src="{{ secure_url('/').'/uploads/files/agotado.png' }}" alt="Agotado" title="Agotado">

                                @endif

                            @endif
                                
                        </div>
                
                        <a href="{{ route('producto', [$producto->slug]) }}" >
                            <h3>{{ $producto->nombre_producto }}</h3>
                        </a>
                            
                        <a href="{{ route('producto', [$producto->slug]) }}" >
                            <h6 class="text-align:center;">{{ $producto->presentacion_producto }}</h6>
                        </a>
                
                        <div class="product_info">

                        @if($descuento==1)

                            @if(isset($precio[$producto->id]))

                                <p id="precio_prod">
                                            
                                    @include('producto_precio_tachado')

                                    <span class="precio_base">${{ number_format($producto->precio_oferta,0,",",".") }}</span>
                                </p>

                                @if($producto->cantidad==null)
                
                                    <a href="{{ route('producto', [$producto->slug]) }}" >
                                            <h6 class="pum c9">{{ $producto->pum }}</h6>
                                    </a>

                                @else

                                    <a href="{{ route('producto', [$producto->slug]) }}" >
                                         <h6 class="pum c10">
                                            {{ $producto->unidad.' a $'.number_format($producto->precio_oferta/$producto->cantidad,2,",",".") }} pesos
                                        </h6>
                                    </a>

                                @endif


                            @else

                                <p id="precio_prod"><span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                @if($producto->cantidad==null)
                                    
                                    <a href="{{ route('producto', [$producto->slug]) }}" >
                                        
                                        <h6 class="pum c11">{{ $producto->pum }}</h6>
                                    </a>

                                @else

                                    <a href="{{ route('producto', [$producto->slug]) }}" >
                                        
                                        <h6 class="pum c12">
                                            {{ $producto->unidad.' a $'.number_format($producto->precio_oferta/$producto->cantidad,2,",",".") }} pesos
                                        </h6>
                                    </a>

                                @endif
                                        

                            @endif

                        @else

                            <p id="precio_prod">
                        
                            @include('producto_precio_tachado')
                        
                            <span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                            @if($producto->cantidad==null)
                            
                                <a href="{{ route('producto', [$producto->slug]) }}" >
                                <h6 class="pum c1">{{ $producto->pum }}</h6>
                                </a>

                            @else

                                <a href="{{ route('producto', [$producto->slug]) }}" >
                                    <h6 class="pum c2">
                                        {{ $producto->unidad.' a $'.number_format($producto->precio_base/$producto->cantidad,2,",",".") }} pesos
                                    </h6>
                                </a>

                            @endif

                        @endif

                        @include('producto_botones')
            
            </div>
        
        </div>
    
    </div>
    @if ($i % 6 == 0)
        </div>
        <div class="row">
    @endif
                   

@endif

@endif

@else

@if(isset($combos[$producto->id]))

@php $i++; @endphp

    <div class="col-md-2 col-sm-6 col-xs-6 ">
        
        <div class="productos">
            
            <div class="text-align:center;">
                
                <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ secure_url('/').'/uploads/productos/250/'.$producto->imagen_producto }}" alt="{{ $producto->nombre_producto }}" title="{{ $producto->nombre_producto }}" class="img-responsive homi"></a>

                @if(isset($inventario[$producto->id]))

                    @if($inventario[$producto->id]<=0)

                        <img class="agotado" style="" src="{{ secure_url('/').'/uploads/files/agotado.png' }}" alt="Agotado" title="Agotado">

                    @endif

                @endif
                                
            </div>
            
            <a href="{{ route('producto', [$producto->slug]) }}" ><h3>{{ $producto->nombre_producto }}</h3></a>
            
            <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="text-align:center;">{{ $producto->presentacion_producto }}</h6></a>
            
            <div class="product_info">

            @if($descuento==1)

                @if(isset($precio[$producto->id]))

                    <p id="precio_prod">
                                            
                        @include('producto_precio_tachado')

                        <span class="precio_base">${{ number_format($producto->precio_oferta,0,",",".") }}</span>
                    </p>

                    @if($producto->cantidad==null)
                        
                        <a href="{{ route('producto', [$producto->slug]) }}" >
                                <h6 class="pum c3">{{ $producto->pum }}</h6>
                        </a>
                            
                    @else

                        <a href="{{ route('producto', [$producto->slug]) }}" >
                            <h6 class="pum c4">
                                    {{ $producto->unidad.' a $'.number_format($producto->precio_base/$producto->cantidad,2,",",".") }} pesos
                            </h6>
                        </a>

                    @endif

                @else

                    <p id="precio_prod"><span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                    @if($producto->cantidad==null)
                        <a href="{{ route('producto', [$producto->slug]) }}" >
                            <h6 class="pum c5">{{ $producto->pum }}</h6>
                        </a>
                        
                    @else

                        <a href="{{ route('producto', [$producto->slug]) }}" >
                            <h6 class="pum c6">
                                {{ $producto->unidad.' a $'.number_format($producto->precio_base/$producto->cantidad,2,",",".") }} pesos
                            </h6>
                        </a>

                    @endif

                @endif

            @else

                <p id="precio_prod">
                    
                @include('producto_precio_tachado')
                
                <span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                @if($producto->cantidad==null)
                    <a href="{{ route('producto', [$producto->slug]) }}" >
                        <h6 class="pum c7">{{ $producto->pum }}</h6>
                    </a>
                    
                @else

                    <a href="{{ route('producto', [$producto->slug]) }}" >
                        <h6 class="pum c8">
                            {{ $producto->unidad.' a $'.number_format($producto->precio_base/$producto->cantidad,2,",",".") }} pesos
                        </h6>
                    </a>

                @endif

            @endif

            @include('producto_botones')
        
        </div>
    
    </div>

</div>
@if ($i % 6 == 0)
    </div>
    <div class="row">
@endif


        @endif
            
    @endif

@endif<!--//endif $i<12-->
