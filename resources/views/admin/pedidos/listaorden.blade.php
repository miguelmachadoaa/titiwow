@if(isset($cart))

 <h3>Detalle de Compra <button type="button" class="btn btn-link vaciarCarrito"> Vaciar Carrito</button></h3>

    @if(count($cart))

    @foreach($cart as $p)

    @if(isset($p->nombre_producto))

        <div class="row table-responsive">
            
             <div class="col-sm-2 col-xs-2" style="padding:0;"><img style="width: 100%;" src="{{secure_url('uploads/productos/60/'.$p->imagen_producto)}}" alt=""></div>            <div class="col-sm-10 col-xs-10">
                <p><b>{{$p->nombre_producto}}</b> <button type="button" data-slug="{{$p->slug}}" class="btn btn-link delcar"><i class="fa fa-trash"></i></button></p>
                <p class="">{{number_format($p->precio_oferta, 0,',', '.')}} x 

                    <select data-id="{{$p->id}}" style="width: 30%" class="  cantidadcarrito" name="cantidad_{{$p->id}}" id="cantidad_{{$p->id}}">
                        <option value="1"  @if($p->cantidad==1) Selected  @endif  >1</option>
                        <option value="2" @if($p->cantidad==2)Selected @endif >2</option>
                        <option value="3" @if($p->cantidad==3)Selected @endif >3</option>
                        <option value="4" @if($p->cantidad==4)Selected @endif >4</option>
                        <option value="5" @if($p->cantidad==5)Selected @endif >5</option>
                        @if($p->cantidad>5)
                            <option Selected value="{{$p->cantidad}}">{{$p->cantidad}}</option>
                        @endif 
                        <option value="0">Otra...</option>
                    </select>

                    
                </p>
                <p>Total: {{number_format($p->cantidad*$p->precio_oferta,0,',','.')}}</p>
                
            </div>
        </div>

         @endif

    @endforeach


    <h4>Total Venta {{number_format($total_venta,0,',','.')}}</h4>


    @if($total_venta>0)

        <a  href="{{secure_url('admin/tomapedidos/checkout')}}" class="btn btn-primary btn-lg" >Ir al Checkout</a>



    @endif
    @endif

 


@endif

<div class="row">
                       {{json_encode($cart)}}
                     </div>