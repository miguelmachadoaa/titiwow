 
@if(isset($producto->id))

<a class="btn btn-md btn-vermas" href="{{ route('producto', [$producto->slug]) }}" style="margin-bottom:5px;">Ver <i class="fa fa-plus" aria-hidden="true"></i></a>

   @if(isset($cart[$producto->slug]))

          <div class="row" style="margin-bottom:5px;">
            <div class="col-sm-10 col-sm-offset-1">
              <div class="input-group">
                <span class="input-group-btn">
                  <button data-slug="{{ $producto->slug }}" data-tipo='resta' data-id="{{ $producto->id }}" class="btn btn-danger updatecart" type="button"><i class="fa fa-minus"></i></button>
                </span>
                <input id="cantidad_{{ $producto->id }}" name="cantidad_{{ $producto->id }}" type="number" step="1" readonly class="form-control" value="{{ $cart[$producto->slug]->cantidad }}" placeholder="">
                <span class="input-group-btn">
                  <button data-slug="{{ $producto->slug }}" data-tipo='suma' data-id="{{ $producto->id }}" class="btn btn-success updatecart" type="button"><i class="fa fa-plus"></i></button>
                  
                </span>
              </div><!-- /input-group -->
            </div><!-- /.col-lg-6 -->
           
          </div><!-- /.row -->

    @else

        <a data-slug="{{ $producto->slug }}" data-price="{{ intval($producto->precio_base) }}" data-id="{{ $producto->id }}" data-name="{{ $producto->nombre_producto }}" data-imagen="{{ secure_url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="btn btn-md btn-cart addtocart" href="{{secure_url('cart/addtocart', [$producto->slug])}}" alt="Agregar al Carrito"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></a>


    @endif


   
   

@endif
