
<div class="col-sm-6">
    
    <h3 style="text-align: left;">Formulario <small>Mensaje Personalizado para su ancheta</small></h3>

    <form action="{{ secure_url('login') }}" class="omb_loginForm"  autocomplete="off" method="POST">
     


        <div class="form-group {{ $errors->first('ancheta_de', 'has-error') }}">
            <label class="sr-only">De:</label>
            <input type="text" class="form-control" maxlength="50" name="ancheta_de" id="ancheta_de" placeholder="De"
                   value="{!! old('ancheta_de') !!}">
        </div>


        <span class="help-block">{{ $errors->first('ancheta_de', ':message') }}</span>


         <div class="form-group {{ $errors->first('ancheta_para', 'has-error') }}">
            <label class="sr-only">Para: </label>
            <input type="text" class="form-control" maxlength="50"  name="ancheta_para" id="ancheta_para" placeholder="Para"
                   value="{!! old('ancheta_para') !!}">
        </div>

        
        <span class="help-block">{{ $errors->first('ancheta_para', ':message') }}</span>


         <div class="form-group {{ $errors->first('ancheta_mensaje', 'has-error') }}">
            <label class="sr-only">Mensaje</label>

            <textarea class="form-control" maxlength="250"  name="ancheta_mensaje" id="ancheta_mensaje" cols="30" rows="10" placeholder="Mensaje">{!! old('ancheta_mensaje') !!}</textarea>
            
        </div>

        
        <span class="help-block">{{ $errors->first('ancheta_mensaje', ':message') }}</span>


       
       
    </form>
</div>
<div class="col-sm-6">
                    
    <div class="">

        @if(count($cartancheta))

            <h3>Productos Seleccionados</h3>

            @foreach($cartancheta as $carta)

               <h6 style="color: #009fe3;text-decoration: none;font-family: 'PlutoBold';"> <i class="fa fa-angle-double-right"></i>{{$carta->nombre_producto}} Precio: ${{number_format($carta->precio_oferta, 0, ',', '.')}}</h6>

            @endforeach

        @endif

        <h3 style="color: #143473;margin-bottom: 15px;">Total de la Ancheta : <span class="totalancheta">COP {{number_format($total, 0, ',', '.')}}</span></h3>

    </div>


    <div class="row">
        
        <div class="col-sm-12">

             <!--a   class="btn btn-md btn-danger reiniciarAncheta" href="{{secure_url('cart/reiniciarancheta')}}" alt="Reiniciar Ancheta ">Reiniciar Ancheta </a-->
            
            <a  style="display: none;" 
                data-slug="{{ $producto->slug }}" 
                data-price="{{ intval($total) }}" 
                data-id="{{ $producto->id }}" 
                data-name="{{ $producto->nombre_producto }}" data-imagen="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="btn btn-md btn-cart addtocartunaancheta" href="{{secure_url('cart/addtocart', [$producto->slug])}}" alt="Comprar Ancheta ">Comprar Ancheta </a>


        </div>
    </div>


</div>