<div class="col-sm-6 col-md-4 co-xs-6 " style=" padding: 0;">

    <div class="row" style="border: 1px solid #f5ecec; -webkit-border-radius: 15px 15px; -moz-border-radius:15px 15px; margin:0.5em;">
        
        <div class="col-sm-5 col-xs-4" style="padding: 0;margin: 0;">
             <a href="{{ route('producto', [$p->slug]) }}" ><img src="{{ secure_url('/').'/uploads/productos/250/'.$p->imagen_producto }}" alt="{{ $p->nombre_producto }}" title="{{ $p->nombre_producto }}" class="img-responsive"></a>

        </div>

        <div class="col-sm-7 col-xs-8 producto">

        <a href="{{ route('producto', [$p->slug]) }}" ><h3 style=" font-size: 1em; margin-top: 5px; " > {{$p->nombre_producto}}</h3></a>


            
                <h6  class="text-align:center; " style=" ">{{ $p->presentacion_producto }}</h6>
            


             <p class="precio_base" style="">Precio: ${{number_format($p->precio_oferta, 0, ',', '.')}}</p>

            <p style="height: 2em;">
               @if(isset($cartancheta[$p->slug]))

                <button data-id="{{$p->id}}" data-slug="{{$p->slug}}"  type="button" class="btn btn-danger deltocartancheta pseleccionado">Quitar</button>

            @else

                <button data-id="{{$p->id}}" data-slug="{{$p->slug}}" data-price="{{$p->precio_oferta}}"  type="button" class="btn btn-primary addtocartancheta">Agregar</button>


            @endif  
            </p>

            

            @if(isset($error))

                @if($error!='' || $error!='0' || strlen($error)<10)

                    <p><small style="color:red">{{$error}}</small></p>

                @endif

            @endif


           

            
                                                            
        </div>

    </div>

</div>