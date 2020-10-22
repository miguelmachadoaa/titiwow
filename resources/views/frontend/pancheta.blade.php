<div class="col-sm-4 co-xs-6 " style=" padding: 0;">

    <div class="row" style="border: 1px solid #f5ecec; -webkit-border-radius: 15px 15px; -moz-border-radius:15px 15px; margin:0.5em;">
        
        <div class="col-sm-5 col-xs-4" style="padding: 0;margin: 0;">
             <a href="{{ route('producto', [$p->slug]) }}" ><img src="{{ secure_url('/').'/uploads/productos/250/'.$p->imagen_producto }}" alt="{{ $p->nombre_producto }}" title="{{ $p->nombre_producto }}" class="img-responsive"></a>

        </div>

        <div class="col-sm-7 col-xs-8">

            <p style="   font-size: 14px;color: #143473;margin: 20px 5px 15px 5px;min-height: 45px; font-family: 'PlutoMedium'; text-align: left;" > {{$p->nombre_producto}}</p>

            <a href="{{ route('producto', [$p->slug]) }}" ><h6 class="text-align:center; " style=" font-size: 11px;color: #199ad9;margin: 20px 0px 15px 0px; font-family: 'PlutoBold';">{{ $p->presentacion_producto }}</h6></a>

            <p style="color: #143473;font-size: 1.2em; font-family: 'Roboto', sans-serif !important;">Precio: {{$p->precio_base}}</p>
            <p style="height: 2em;">
               @if(isset($cartancheta[$p->slug]))

                <button data-id="{{$p->id}}" data-slug="{{$p->slug}}"  type="button" class="btn btn-success deltocartancheta pseleccionado">Seleccionado</button>

            @else

                <button data-id="{{$p->id}}" data-slug="{{$p->slug}}" data-price="{{$p->precio_base}}"  type="button" class="btn btn-primary addtocartancheta">Agregar</button>


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