    <div class="row" style="    margin: 15px;">

        <h2>Carrito de Compras</h2>

        <a class="btn  btn-link" href="{{secure_url('cart/vaciar')}}">Vaciar</a>

        @if(count($cart))

            <div class="row">



                @foreach($cart as $row)
                    <hr>
                    <div class="row" style="text-align: left;">
                        
                        <div class="col-sm-2 col-xs-3">
                            <a target="_blank"  href="{{ route('producto', [$row->slug]) }}" ><img style="padding: 5px 0px; height: 8em;"  src="../uploads/productos/{{$row->imagen_producto}}"></a>
                        </div>

                        <div class="col-sm-10 col-xs-9">
                            <div class="col-xs-12"><a target="_blank"  href="{{ route('producto', [$row->slug]) }}" >{{$row->nombre_producto}}</a></div>    
                            <div class="col-xs-12">Precio: {{ number_format($row->precio_oferta, 0,",",".") }}</div>    
                            <div class="col-xs-12">Subtotal:{{ number_format($row->cantidad*$row->precio_oferta, 0,",",".") }}</div>    
                            <div class="col-xs-8">
                                Cantidad: <input 
                                    style="text-align: center;" 
                                    class="cantidad" 
                                    type="number" 
                                    data-id="{{$row->id}}" 
                                    data-slug="{{$row->slug}}" 
                                    data-url="{{secure_url('cart/updatecart')}}" 
                                    data-href="{{secure_url('cart/update', [$row->slug])}}"
                                    name="producto_{{$row->id}}"
                                    id="producto_{{$row->id}}"
                                    min="1"
                                    max="100"
                                    value="{{ $row->cantidad }}" 
                                    >
                            </div>
                            <div class="col-xs-4"><a class="btn btn-danger btn-xs" href="{{secure_url('cart/delete', [$row->slug])}}">Borrar</a></div>
                            
                           
                        </div>

                        <div class="col-sm-2 col-xs-3">
                            
                        </div>


                    </div>

                            
                    
                    @endforeach

                <hr>

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

    


     @else


    <h1><span class="label label-primary">Tu Carrito está Vacio</span></h1>
<br />
        <p style="text-align: center;">
           
            <a class="label label-seguir" href="{{secure_url('productos')}}">Seguir Comprando <i class="fa fa-plus" aria-hidden="true"></i></a>

        </p> 

        

     @endif

     <hr>