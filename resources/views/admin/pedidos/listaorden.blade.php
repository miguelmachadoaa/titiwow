@if(isset($cart))

<hr>

    @if(count($cart))
        <div class="row listaproductoscart" style="    height: 30em;   overflow: auto;">


        @foreach($cart as $p)

            @if(isset($p->nombre_producto))

            <div class="row">

                <div class="col-sm-2 col-xs-2" style="padding:0;"><img style="width: 100%;" src="{{secure_url('uploads/productos/60/'.$p->imagen_producto)}}" alt=""></div>            
                
                <div class="col-sm-10 col-xs-10">

                    <div style="display:flex;  justify-content: space-between; align-items: center;">
                        <div>
                        <b> {{$p->nombre_producto}}</b>
                        </div>
                        <div>
                            <button style="color: #dc143c;" type="button" data-slug="{{$p->slug}}" class="btn btn-link delcar"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>


                    <div style="display:flex;  justify-content: space-between; align-items: center;">
                        <div>
                        {{number_format($p->precio_oferta, 0,',', '.')}} x 
                        </div>
                        <div>
                        <div class="input-group">
                                            
                                            <span class="input-group-btn">
                                                
                                                <button style="" data-cantidad="{{$p->cantidad}}" data-slug="{{ $p->slug }}" data-tipo='resta' data-id="{{ $p->id }}" class="btn btn-danger updatecart" type="button"><i class="fa fa-minus"></i></button>
                
                                            </span>
                
                                            <input  id="cantidad_{{ $p->id }}" name="cantidad_{{ $p->id }}" type="number" step="1" readonly class="form-control" value="{{$p->cantidad}}" placeholder="">
                
                
                                            <span class="input-group-btn">
                
                                                <button data-cantidad="{{$p->cantidad}}" data-slug="{{ $p->slug }}" data-tipo='suma' data-id="{{ $p->id }}" class="btn btn-success updatecart" type="button"><i class="fa fa-plus"></i></button>
                
                                            </span>
                
                                        </div>
                        </div>
                    </div>

                    <p style="margin:0;" ><b>  Total: {{number_format($p->cantidad*$p->precio_oferta,0,',','.')}}</b></p>
                       
                        <!-- /input-group -->
        
                        <!--select data-id="{{$p->id}}" style="width: 30%" class="  cantidadcarrito" name="cantidad_{{$p->id}}" id="cantidad_{{$p->id}}">
                                <option value="1"  @if($p->cantidad==1) Selected  @endif  >1</option>
                                <option value="2" @if($p->cantidad==2)Selected @endif >2</option>
                                <option value="3" @if($p->cantidad==3)Selected @endif >3</option>
                                <option value="4" @if($p->cantidad==4)Selected @endif >4</option>
                                <option value="5" @if($p->cantidad==5)Selected @endif >5</option>
                                @if($p->cantidad>5)
                                    <option Selected value="{{$p->cantidad}}">{{$p->cantidad}}</option>
                                @endif 
                                <option value="0">Otra...</option>
                            </select-->
                        
                </div>

                        
                    

                <hr>

            </div>

                
                
        

             @endif

        @endforeach

        </div>


        <h4>Total Venta {{number_format($total_venta,0,',','.')}}</h4>


        @if($total_venta>0)

            <a style="width:100%"  href="{{secure_url('admin/tomapedidos/checkout')}}" class="btn btn-primary btn-lg" >Ir al Checkout</a>
        
        @endif

        <div class="row">
            <div class="col-sm-4 p-0 ">
                <button style="width:100%" type="button" class="btn btn-warning vaciarCarrito">Vaciar</button>
            </div>      
            <div  class="col-sm-4 p-0 ">
            <button style="width:100%" type="button" class="btn btn-info aplicarCupon"> Cupon</button>

                    
            </div>
            <div  class="col-sm-4 p-0 ">
            <button style="width:100%" type="button" class="btn btn-danger cancelarPedido"> Cancelar</button>

            </div>
        </div>

    @endif

 


@endif

<!--div class="row">
                       {{json_encode($cart)}}
                     </div-->