@if($producto->precio_oferta>0)
<div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="productos">
                        <div class="text-align:center;">
                            <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="img-responsive"></a>

                           <!--p>{{$producto->order}}</p> 
                                <p>{{$producto->updated_at}}</p-->

                            
                            @if($producto->tipo_producto=='1')

                                @if(isset($inventario[$producto->id]))

                                    @if($inventario[$producto->id]<=0)

                                        <img class="agotado" style="" src="{{ secure_url('/').'/uploads/files/agotado.png' }}" alt="">

                                    @endif

                                @endif

                            @else

                                @if(isset($combos[$producto->id]))

                                @else

                                        <img class="agotado" style="" src="{{ secure_url('/').'/uploads/files/agotado.png' }}" alt="">

                                @endif


                            @endif


                        </div>
                        <a href="{{ route('producto', [$producto->slug]) }}" ><h3>{{ $producto->nombre_producto }}</h3></a>
                        <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="text-align:center;">{{ $producto->presentacion_producto }}</h6></a>
                        <div class="product_info">
                            
                            @if($descuento==1)

                                @if(isset($precio[$producto->id]))

                                    @switch($precio[$producto->id]['operacion'])

                                        @case(1)

                                            <p id="precio_prod"><span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>
                                            
                                            @break

                                        @case(2)

                                            <p id="precio_prod">
                                                @if($almacen->descuento_productos=='1')
                                                <del class="">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;

                                                @endif


                                                

                                                <span class="precio_base">${{ number_format($producto->precio_base*(1-($precio[$producto->id]['precio']/100)),0,",",".") }}</span></p>
                                            @break

                                        @case(3)

                                            <p id="precio_prod">
                                                @if($almacen->descuento_productos=='1')
                                                    <del class="">${{ number_format($producto->precio_base,0,",",".") }}</del>&nbsp;

                                                @endif

        
                                                

                                                <span class="precio_base">${{ number_format($precio[$producto->id]['precio'],0,",",".") }}</span></p>
                                            @break

                                        
                                    @endswitch

                                    <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="pum">{{ $precio[$producto->id]['pum'] }}</h6></a>

                                @else

                                    <p id="precio_prod"><span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".") }}</span></p>

                                    <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="pum">{{ $producto->pum }}</h6></a>

                                @endif


                               

                            @else

                                <p id="precio_prod">
                                    @if($almacen->descuento_productos=='1')
                                        <del class="@if($descuento==1) hidden @endif">${{ number_format($producto->precio_base,0,",",".").' -'.$producto->operacion }}</del>&nbsp;
                                    @endif


                                    

                                    <span class="precio_base">${{ number_format($producto->precio_base*$descuento,0,",",".").' -'.$producto->operacion }}</span></p>

                                <a href="{{ route('producto', [$producto->slug]) }}" ><h6 class="pum">{{ $producto->pum }}</h6></a>

                            @endif
                            
                            <div class="product_botones boton_{{ $producto->id }}">

                        @if($producto->tipo_producto=='1')  
                        
                            @if(isset($inventario[$producto->id]))
                              
                                @if($inventario[$producto->id]>0)

                                    @if(isset($cart[$producto->slug]))

                                    <div class="row" style="margin-bottom:5px;">
                                    <div class="col-sm-10 col-sm-offset-1">
                                        <div class="input-group">
                                        <span class="input-group-btn">
                                            
                                            <button data-cantidad="{{ $cart[$producto->slug]->cantidad }}" data-slug="{{ $producto->slug }}" data-tipo='resta' data-id="{{ $producto->id }}" class="btn btn-danger updatecart" type="button"><i class="fa fa-minus"></i></button>

                                        </span>

                                        <input id="cantidad_{{ $producto->id }}" name="cantidad_{{ $producto->id }}" type="number" step="1" readonly class="form-control" value="{{ $cart[$producto->slug]->cantidad }}" placeholder="">


                                        <span class="input-group-btn">

                                            @if($configuracion->maximo_productos==$cart[$producto->slug]->cantidad) 

                                                <button disabled="disabled" data-cantidad="{{ $cart[$producto->slug]->cantidad }}" data-slug="{{ $producto->slug }}" data-tipo='suma' data-id="{{ $producto->id }}" class="btn btn-success " type="button"><i class="fa fa-plus"></i></button>

                                            @else

                                                <button data-cantidad="{{ $cart[$producto->slug]->cantidad }}" data-slug="{{ $producto->slug }}" data-tipo='suma' data-id="{{ $producto->id }}" class="btn btn-success updatecart" type="button"><i class="fa fa-plus"></i></button>

                                             @endif 

                                            

                                            

                                        </span>

                                        </div><!-- /input-group -->
                                    </div><!-- /.col-lg-6 -->
                                    
                                    </div><!-- /.row -->

                                    <a class="btn btn-md btn-vermas" href="{{ route('producto', [$producto->slug]) }}" style="margin-bottom:5px;">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>

                                    @else

                                    <a class="btn btn-md btn-vermas" href="{{ route('producto', [$producto->slug]) }}">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>
                                    <a data-slug="{{ $producto->slug }}" data-price="{{ intval($producto->precio_oferta) }}" data-id="{{ $producto->id }}" data-name="{{ $producto->nombre_producto }}" data-imagen="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="btn btn-md btn-cart addtocart" href="{{secure_url('cart/addtocart', [$producto->slug])}}" alt="Agregar al Carrito"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></a>

                                    @endif

                                @else <!-- si hay inventario  -->

                                    <a class="btn btn-md btn-vermas" href="{{ route('producto', [$producto->slug]) }}">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>
                                
                                @endif

                            @endif

                        @else

                        <!-- Proceso para productos de tipo combo -->

                            @if(isset($combos[$producto->id]))
                              
                                @if($inventario[$producto->id]>0)

                                    @if(isset($cart[$producto->slug]))

                                    <div class="row" style="margin-bottom:5px;">
                                    <div class="col-sm-10 col-sm-offset-1">
                                        <div class="input-group">
                                        <span class="input-group-btn">
                                            
                                            <button data-cantidad="{{ $cart[$producto->slug]->cantidad }}" data-slug="{{ $producto->slug }}" data-tipo='resta' data-id="{{ $producto->id }}" class="btn btn-danger updatecart" type="button"><i class="fa fa-minus"></i></button>

                                        </span>

                                        <input id="cantidad_{{ $producto->id }}" name="cantidad_{{ $producto->id }}" type="number" step="1" readonly class="form-control" value="{{ $cart[$producto->slug]->cantidad }}" placeholder="">


                                        <span class="input-group-btn">

                                            <button data-cantidad="{{ $cart[$producto->slug]->cantidad }}" data-slug="{{ $producto->slug }}" data-tipo='suma' data-id="{{ $producto->id }}" class="btn btn-success updatecart" type="button"><i class="fa fa-plus"></i></button>

                                            

                                        </span>

                                        </div><!-- /input-group -->
                                    </div><!-- /.col-lg-6 -->
                                    
                                    </div><!-- /.row -->

                                    <a class="btn btn-md btn-vermas" href="{{ route('producto', [$producto->slug]) }}" style="margin-bottom:5px;">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>

                                    @else

                                    <a class="btn btn-md btn-vermas" href="{{ route('producto', [$producto->slug]) }}">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>
                                    <a data-slug="{{ $producto->slug }}" data-price="{{ intval($producto->precio_oferta) }}" data-id="{{ $producto->id }}" data-name="{{ $producto->nombre_producto }}" data-imagen="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="btn btn-md btn-cart addtocart" href="{{secure_url('cart/addtocart', [$producto->slug])}}" alt="Agregar al Carrito"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></a>

                                    @endif

                                @else <!-- si hay inventario  -->

                                    <a class="btn btn-md btn-vermas" href="{{ route('producto', [$producto->slug]) }}">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>
                                
                                @endif

                            @else <!-- si hay inventario  -->

                                <a class="btn btn-md btn-vermas" href="{{ route('producto', [$producto->slug]) }}">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>
                            
                            @endif


                    
                        @endif

                                </div>
                        </div>
                    </div>
                </div>

@endif