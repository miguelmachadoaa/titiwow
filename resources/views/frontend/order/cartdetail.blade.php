<div class="row">
    
    <div class="col-sm-6" style="border-right: 1px solid #ddd;">

        @if($error=='0')

        <div class="col-sm-6">
            <img class="img img-responsive" src="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}">
        </div>
        <div class="col-sm-6">
            <h3 style="font-size: 1.3em;">{{ $producto->nombre_producto }}</h3>
            <p> <b>COP{{ number_format($producto->precio_oferta, 0, '.', ',')}}</b></p>
            <p>Cantidad: {{ $producto->cantidad }}</p>
        </div>

        @else

        <h3>{{ $error }}</h3>

        @endif

        
        

    </div>
    <div class="col-sm-6">

        <input type="hidden" name="modal_cantidad" id="modal_cantidad" value="{{ $cantidad }}">

       <p>Tienes un total de {{ $cantidad }} items en tu carro </p> 

       <p>Sub Total: {{ number_format($total, 0, '.', ',') }}</p>

       <p> Total: {{ number_format($total, 0, '.', ',') }}</p>
        
    </div>


</div>