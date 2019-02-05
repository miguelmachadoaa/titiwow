<div class="col-lg-3 col-md-4 col-sm-6 woman bags">

                        <div class="l_product_item">
                                <div class="l_p_img">
                                    <a href="{{ route('producto', [$producto->slug]) }}"><img class="img-fluid" src="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" alt=""></a>
                                </div>
                                <div class="l_p_desc">

                                          <h4 style="text-align: center; padding:1em 0em;">{{ $producto->nombre_producto }}</h4>
                                   
                                    @if($producto->precio_base!=$producto->precio_oferta)

                                        <h5 style="padding:0.5em; background: #000; color: #fff; border-radius: 5px;">  Descuento {{ (1-($producto->precio_oferta/$producto->precio_base))*100 }} % </h5>

                                        <h3 style="color: #dd3333; text-align: center; "><del>{{ $producto->precio_base }}</del>  {{ $producto->precio_oferta }}</h3>

                                    @else
                                          <h3 style="color: #dd3333; text-align: center;">{{ $producto->precio_oferta }}</h3>
                                    @endif


                                    <div style="padding: 2em;">   

                                            <img src="{{ secure_url('/').'/uploads/marcas/'.$producto->imagen_marca }}" class="img-responsive" title="{{ $producto->nombre_producto }}" alt="{{ $producto->nombre_producto }}">

                                    </div>


                                    <ul>




                                    @if($producto->id_tipo_producto == 1)




                                        @if(isset($cart[$producto->slug]))

                                      

                                            
                                           <li class="boton_{{ $producto->id }} ">
                                               <div class="row" style="margin-bottom:5px;">
                                                      <div class="col-sm-10 col-sm-offset-1">
                                                        <div class="input-group">
                                                          <span class="input-group-btn">
                                                            
                                                            <button style="border-radius: 0;" data-slug="{{ $producto->slug }}" data-tipo='resta' data-id="{{ $producto->id }}" class="btn btn-default updatecart" type="button"><i class="fa fa-minus"></i></button>

                                                          </span>

                                                          <input id="cantidad_{{ $producto->id }}" name="cantidad_{{ $producto->id }}" type="number" step="1" readonly class="form-control" value="{{ $cart[$producto->slug]->cantidad }}" placeholder="">


                                                          <span class="input-group-btn">

                                                            <button style="border-radius: 0;"  data-slug="{{ $producto->slug }}" data-tipo='suma' data-id="{{ $producto->id }}" class="btn btn-default updatecart" type="button"><i class="fa fa-plus"></i></button>

                                                            

                                                          </span>

                                                        </div><!-- /input-group -->
                                                      </div><!-- /.col-lg-6 -->
                                                     
                                                    </div><!-- /.row -->
                                           </li>

                                        @else

                                            <li class="boton_{{ $producto->id }} ">
                                                <a data-slug="{{ $producto->slug }}" data-price="{{ intval($producto->precio_oferta) }}" data-id="{{ $producto->id }}" data-name="{{ $producto->nombre_producto }}" data-imagen="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class=" addtocart add_cart_btn btn-cart" href="{{secure_url('cart/addtocart', [$producto->slug])}}" alt="Agregar al Carrito"  >Agregar</a>

                                                <!--a class="add_cart_btn btn-cart" href="{{ route('producto', [$producto->slug]) }} ">Ver Producto</a-->
                                            </li>

                                        @endif
                                        
                                    @else

                                        <li class="boton_{{ $producto->id }} ">
                                            <a class="add_cart_btn btn-cart"  href="{{ $producto->link_producto }}" class="add_cart_btn btn-cart" target="_blank" alt="Ver Producto"  >Ver Producto</a>
                                        </li>

                                        
                                    @endif

                                    </ul>
                                    
                                </div>
                            </div>
                        </div>