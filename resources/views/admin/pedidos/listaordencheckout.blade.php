@if(isset($cart))

 <h3>Detalle de Compra  <a class="btn btn-link" href="{{secure_url('admin/pedidos/')}}"><i class="fa fa-edit"></i></a></h3>

    @if(count($cart))

    @foreach($cart as $p)

    @if(isset($p->nombre_producto))

        <div class="row">
            
            <div class="col-sm-2 col-xs-2" style="padding:0;"><img style="width: 100%;" src="{{secure_url('uploads/productos/60/'.$p->imagen_producto)}}" alt=""></div>   
            <div class="col-sm-10 col-xs-10 ">
                <p><b>{{$p->nombre_producto}}</b></p>
                <p class="">{{number_format($p->precio_base,0,',','.')}} x {{$p->cantidad}} </p>
                <p>Total: {{number_format($p->cantidad*$p->precio_base,0,',','.')}}</p>
                
            </div>
        </div>

         @endif

    @endforeach

    <h4>Total Venta {{number_format($total_venta,0,',','.')}}</h4>

    @if($total_venta>0)

        @if(isset($cart['id_cliente']) && isset($cart['id_direccion']))

            <a  href="{{secure_url('admin/pedidos/procesar')}}" class="btn btn-primary btn-lg" >Finalizar Compra</a>

        @else

            <button type="button" class="btn btn-danger">Debes Seleccionar un Cliente </button>

        @endif



    @endif
    @endif

 


@endif