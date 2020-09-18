@if(isset($cart))

 <h3>Detalle de Compra</h3>

    @foreach($cart as $p)

        <div class="row">
            
            <div class="col-sm-2"><img style="width: 60px;" src="{{secure_url('uploads/productos/60/'.$p->imagen_producto)}}" alt=""></div>
            <div class="col-sm-10">
                <p><b>{{$p->nombre_producto}}</b></p>
                <p class="">{{$p->precio_base}} x 

                    <select data-id="{{$p->id}}" style="width: 30%" class="  cantidadcarrito" name="cantidad_{{$p->id}}" id="cantidad_{{$p->id}}">
                        <option value="1"  @if($p->cantidad==1) Selected  @endif  >1</option>
                        <option value="2" @if($p->cantidad==2)Selected @endif >2</option>
                        <option value="3" @if($p->cantidad==3)Selected @endif >3</option>
                        <option value="4" @if($p->cantidad==4)Selected @endif >4</option>
                        <option value="5" @if($p->cantidad==5)Selected @endif >5</option>
                        @if($p->cantidad>5)
                            <option value=" {{$p->cantidad}}">{{$p->cantidad}}</option>
                        @endif 
                        <option value="0">Otra...</option>
                    </select>

                    
                </p>
                <p>Total: {{$p->cantidad*$p->precio_base}}</p>
                
            </div>
        </div>

    @endforeach


    <h4>Total Venta {{$total_venta}}</h4>


    @if($total_venta>0)

        <a class="btn btn-primary btn-lg" href="{{secure_url('admin/pedidos/checkout')}}">Finalizar Compra</a>

    @endif

 


@endif