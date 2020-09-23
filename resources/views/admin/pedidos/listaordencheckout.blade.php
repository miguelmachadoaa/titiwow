@if(isset($cart))

 <h3>Detalle de Compra</h3>

    @if(count($cart))

    @foreach($cart as $p)

    @if(isset($p->nombre_producto))

        <div class="row">
            
            <div class="col-sm-2"><img style="width: 60px;" src="{{secure_url('uploads/productos/60/'.$p->imagen_producto)}}" alt=""></div>
            <div class="col-sm-10">
                <p><b>{{$p->nombre_producto}}</b></p>
                <p class="">{{$p->precio_base}} x {{$p->cantidad}} </p>
                <p>Total: {{$p->cantidad*$p->precio_base}}</p>
                
            </div>
        </div>

         @endif

    @endforeach

    <h4>Total Venta {{$total_venta}}</h4>

    @if($total_venta>0)

        @if(isset($cart['id_cliente']) && isset($cart['id_direccion']))

            <a  href="{{secure_url('admin/pedidos/procesar')}}" class="btn btn-primary btn-lg" >Finalizar Compra</a>

        @else

            <button type="button" class="btn btn-danger">Debes Seleccionar un Cliente </button>

        @endif



    @endif
    @endif

 


@endif