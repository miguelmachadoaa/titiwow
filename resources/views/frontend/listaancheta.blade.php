<div class="col-sm-12">
                    
    <div class="">

        @if(count($cartancheta))

            <h3>Productos Seleccionados</h3>

            @foreach($cartancheta as $carta)

                <h6 style="color: #009fe3;text-decoration: none;font-family: 'PlutoBold';"> <i class="fa fa-angle-double-right"></i>{{$carta->nombre_producto}} Precio: {{$carta->precio_base}}</h6>

            @endforeach

        @endif

        <h3>Total de la Ancheta : <span class="totalancheta">{{number_format($total, 0, ',', '.')}}</span></h3>

    </div>


    <div class="row">
        
        <div class="col-sm-12">
            
            <a  style="display: none;" 
                data-slug="{{ $producto->slug }}" 
                data-price="{{ intval($total) }}" 
                data-id="{{ $producto->id }}" 
                data-name="{{ $producto->nombre_producto }}" data-imagen="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="btn btn-md btn-cart addtocartunaancheta" href="{{secure_url('cart/addtocart', [$producto->slug])}}" alt="Comprar Ancheta ">Comprar Ancheta </a>


        </div>
    </div>


</div>