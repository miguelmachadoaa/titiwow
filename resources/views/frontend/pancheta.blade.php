<div class="col-sm-4 co-xs-6 " style=" border: 1px solid #f5ecec; -webkit-border-radius: 15px 15px; -moz-border-radius:15px 15px;">

    <div class="row" style="">
        
        <div class="col-sm-5 col-xs-4" style="padding: 0;margin: 0;">
             <a href="{{ route('producto', [$p->slug]) }}" ><img src="{{ secure_url('/').'/uploads/productos/250/'.$p->imagen_producto }}" alt="{{ $p->nombre_producto }}" title="{{ $p->nombre_producto }}" class="img-responsive"></a>

        </div>

        <div class="col-sm-7 col-xs-8">

            <p> {{$p->nombre_producto}}</p>

            <a href="{{ route('producto', [$p->slug]) }}" ><h6 class="text-align:center;">{{ $p->presentacion_producto }}</h6></a>

            <p>Precio: {{$p->precio_base}}</p>

            @if(isset($cartancheta[$p->slug]))

                <button data-id="{{$p->id}}" data-slug="{{$p->slug}}"  type="button" class="btn btn-success pseleccionado">Seleccionado</button>

            @else

                <button data-id="{{$p->id}}" data-slug="{{$p->slug}}" data-price="{{$p->precio_base}}"  type="button" class="btn btn-primary addtocartancheta">Agregar</button>


            @endif 


           

            
                                                            
        </div>

    </div>

</div>