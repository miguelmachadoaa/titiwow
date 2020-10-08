    <div class="row" style="    margin: 15px;">

      @if(isset($aviso))
        @if($aviso!='')

        <div class="alert alert-danger">
            {{ $aviso }}
        </div>

        @endif
    @endif

     @if(isset($error))
        @if($error!='0')

        <div class="alert alert-danger">
            {{ $error }}
        </div>

        @endif
    @endif

        <h1>Carrito de Compras</h1>

        <a style="color: #d5006e !important; " class="btn  btn-link" href="{{secure_url('cart/vaciar')}}">Vaciar</a>

        @if(count($cart))

                <div class="row" style="    padding: 1em;">

                    @php $ban_disponible=0; @endphp 

                    @php $ban_promocion=0; @endphp 

                    @foreach($cart as $row)

                        @if(isset($row->promocion))

                        @php $ban_promocion=1; @endphp 
                        @else




                        <hr>
                        <div class="row @if($row->disponible==0) {{'nodisponible'}} @endif " style="text-align: left;">
                            
                            <div class="col-sm-2 col-xs-4">
                                <a target="_blank"  href="{{ route('producto', [$row->slug]) }}" ><img style="padding: 5px 0px; height: 8em;"  src="../uploads/productos/250/{{$row->imagen_producto}}" alt="{{ $row->nombre_producto }}" title="{{ $row->nombre_producto }}"></a>
                            </div>

                            <div class="col-sm-6 col-xs-8">

                                <div class="col-xs-12" style="margin-bottom:5px;">

                                    <h4><a target="_blank"  href="{{ route('producto', [$row->slug]) }}" >{{$row->nombre_producto}}</a></h4>

                                    @if($row->disponible==0)

                                        @php $ban_disponible=1; @endphp 

                                        <h4><a style="color: #f70072;" target="_blank"  href="{{ route('producto', [$row->slug]) }}" >Este producto no esta disponible para su dirección de envio</a></h4>

                                        @endif

                                </div>    

                                <div class="col-xs-12" style="margin-bottom:10px;">

                                    <b style="color: #143473;">Precio:</b> {{ number_format($row->precio_oferta, 0,",",".") }}

                                </div>    

                                <div class="col-xs-12" style="margin-bottom:10px;">
                                    <b style="color: #143473;">Subtotal:</b> {{ number_format($row->cantidad*$row->precio_oferta, 0,",",".") }}

                                </div>    

                                @if($row->tipo_producto=='2')

                                    @if(isset($combos[$row->id]))

                                    <b style="color: #143473; padding-left: 1em;">Incluye:</b>

                                        @foreach($combos[$row->id] as $cp)

                                            <div class="col-xs-12" style=" @if($loop->last) {{'margin-bottom:10px;'}} @endif padding-left: 4em;">

                                                <h6><a target="_blank"  href="{{ route('producto', [$cp->slug]) }}" ><i class="fa fa-angle-double-right"></i>{{$cp->cantidad.'- '.$cp->nombre_producto}}</a></h6>

                                            </div>  

                                        @endforeach

                                    @endif


                                @endif
                               
                               
                                
                               
                            </div>

                            <div class="col-sm-2 col-xs-9">


                                   <div class="input-group">
                                  <span class="input-group-btn">
                                    
                                    <button data-cantidad="{{ $row->cantidad }}" data-slug="{{ $row->slug }}" data-tipo='resta' data-id="{{ $row->id }}" class="btn btn-danger updatecartdetalle" type="button"><i class="fa fa-minus"></i></button>

                                  </span>

                                  <input id="cantidad_{{ $row->id }}" name="cantidad_{{ $row->id }}" type="number" step="1" readonly class="form-control" value="{{ $row->cantidad }}" placeholder="">


                                  <span class="input-group-btn">


                                        @if($configuracion->maximo_productos==$row->cantidad) 

                                        <button disabled="disabled" data-cantidad="{{ $row->cantidad }}" data-slug="{{ $row->slug }}" data-tipo='suma' data-id="{{ $row->id }}" class="btn btn-success " type="button"><i class="fa fa-plus"></i></button>

                                        @else

                                        <button data-cantidad="{{ $row->cantidad }}" data-slug="{{ $row->slug }}" data-tipo='suma' data-id="{{ $row->id }}" class="btn btn-success updatecartdetalle" type="button"><i class="fa fa-plus"></i></button>

                                         @endif 

                                  </span>

                                </div><!-- /input-group -->
                                
                            </div>

                            <div class="col-sm-2 col-xs-3">
                                <a class="btn btn-danger btn-xs" href="{{secure_url('cart/delete', [$row->slug])}}">Borrar</a>
                            </div>




                        </div>

                                
                        @endif
                        @endforeach














                        @if($ban_promocion==1)

                        <h3>Obsequios por promociones</h3>

                        @foreach($cart as $row)

                        @if(isset($row->promocion))

                        
                        <hr>
                        <div class="row  " style="text-align: left;">
                            
                            <div class="col-sm-2 col-xs-4">
                                <a target="_blank"  href="{{ route('producto', [$row->slug]) }}" ><img style="padding: 5px 0px; height: 8em;"  src="../uploads/productos/250/{{$row->imagen_producto}}" alt="{{ $row->nombre_producto }}" title="{{ $row->nombre_producto }}"></a>
                            </div>

                            <div class="col-sm-6 col-xs-8">

                                <div class="col-xs-12" style="margin-bottom:5px;">

                                    <h4><a target="_blank"  href="{{ route('producto', [$row->slug]) }}" >{{$row->nombre_producto}}</a></h4>

                                </div>    

                                <div class="col-xs-12" style="margin-bottom:10px;">

                                    <b style="color: #143473;">Precio:</b> {{ number_format($row->precio_oferta, 0,",",".") }}

                                </div>    

                                <div class="col-xs-12" style="margin-bottom:10px;">
                                    <b style="color: #143473;">Subtotal:</b> {{ number_format($row->cantidad*$row->precio_oferta, 0,",",".") }}

                                </div>    

                                @if($row->tipo_producto=='2')

                                    @if(isset($combos[$row->id]))

                                    <b style="color: #143473; padding-left: 1em;">Incluye:</b>

                                        @foreach($combos[$row->id] as $cp)

                                            <div class="col-xs-12" style=" @if($loop->last) {{'margin-bottom:10px;'}} @endif padding-left: 4em;">

                                                <h6><a target="_blank"  href="{{ route('producto', [$cp->slug]) }}" ><i class="fa fa-angle-double-right"></i>{{$cp->cantidad.'- '.$cp->nombre_producto}}</a></h6>

                                            </div>  

                                        @endforeach

                                    @endif


                                @endif
                               
                               
                                
                               
                            </div>

                            <!--div class="col-sm-2 col-xs-9">


                                   <div class="input-group">
                                  <span class="input-group-btn">
                                    
                                    <button data-cantidad="{{ $row->cantidad }}" data-slug="{{ $row->slug }}" data-tipo='resta' data-id="{{ $row->id }}" class="btn btn-danger updatecartdetalle" type="button"><i class="fa fa-minus"></i></button>

                                  </span>

                                  <input id="cantidad_{{ $row->id }}" name="cantidad_{{ $row->id }}" type="number" step="1" readonly class="form-control" value="{{ $row->cantidad }}" placeholder="">


                                  <span class="input-group-btn">


                                        @if($configuracion->maximo_productos==$row->cantidad) 

                                        <button disabled="disabled" data-cantidad="{{ $row->cantidad }}" data-slug="{{ $row->slug }}" data-tipo='suma' data-id="{{ $row->id }}" class="btn btn-success " type="button"><i class="fa fa-plus"></i></button>

                                        @else

                                        <button data-cantidad="{{ $row->cantidad }}" data-slug="{{ $row->slug }}" data-tipo='suma' data-id="{{ $row->id }}" class="btn btn-success updatecartdetalle" type="button"><i class="fa fa-plus"></i></button>

                                         @endif 

                                  </span>

                                </div>
                                
                            </div>

                            <div class="col-sm-2 col-xs-3">
                                <a class="btn btn-danger btn-xs" href="{{secure_url('cart/delete', [$row->slug])}}">Borrar</a>
                            </div!-->




                        </div>

                                
                        @endif
                        @endforeach

                        @endif




















                    <hr>

                            @if(isset($almacen->id))
                                @if($almacen->mensaje_promocion!=null && $almacen->mensaje_promocion!='')

                                    <div class="col-sm-12">

                                        <h4 style="color: #d5006e;">{{$almacen->mensaje_promocion}} </h4>

                                    </div>

                                    <hr>

                                @endif
                            @endif


                            @if(isset($ban_disponible))

                                @if($ban_disponible==1)

                                    <div class="col-sm-12">

                                    <h4 style="color: #d5006e;">Debes Eliminar los productos que no estan disponible para su dirección de envio, o asignar una dirección  que concuerde con la ubicación seleccionada en la tienda.  <a href="{{secure_url('misdirecciones')}}"> Ir a mis direcciones</a> </h4>

                                </div>

                                <hr>

                                @endif
                            @endif



                    <div class="col-xs-4 col-sm-4">
                        <h3>Total</h3>
                    </div>

                    <div class="col-xs-8 col-sm-8">
                        <h3> COP {{number_format($total, 0,",",".")}} </h3>
                    </div>

                    <hr>

                    

           

           


                </div>

     <input type="hidden" name="total_orden" id="total_orden" value="{{ $total }}">
            <input type="hidden" name="limite_orden" id="limite_orden" value="{{ $configuracion->minimo_compra }}">

                       

        </div>

        <p style="text-align: center;">
            <a class="label label-seguir" href="{{secure_url('productos')}}">Seguir Comprando <i class="fa fa-plus" aria-hidden="true"></i></a>

             <a class="btn btn-cart sendDetail" href="{{secure_url('order/detail')}}">Finalizar Tu Compra <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
         </p> 

        
         <div class="row">
             <div class="col-sm-12">
                 {{json_encode($cart)}}
             </div>
         </div>

     @else


        <h1><span class="label label-primary">Tu Carrito está Vacio</span></h1>
        <br />
        <p style="text-align: center;">
           
            <a class="label label-seguir" href="{{secure_url('productos')}}">Seguir Comprando <i class="fa fa-plus" aria-hidden="true"></i></a>

        </p> 

        

     @endif

     <hr>