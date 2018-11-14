 
@if(isset($producto->id))



   @if(isset($cart[$producto->slug]))

          <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
              <div class="input-group">
                <span class="input-group-btn">
                  <button data-slug="{{ $producto->slug }}" data-tipo='suma' data-id="{{ $producto->id }}" class="btn btn-success updatecart" type="button"><i class="fa fa-plus"></i></button>
                </span>
                <input id="cantidad_{{ $producto->id }}" name="cantidad_{{ $producto->id }}" type="number" step="1" readonly class="form-control" value="{{ $cart[$producto->slug]->cantidad }}" placeholder="">
                <span class="input-group-btn">
                  <button data-slug="{{ $producto->slug }}" data-tipo='resta' data-id="{{ $producto->id }}" class="btn btn-success updatecart" type="button"><i class="fa fa-minus"></i></button>
                </span>
              </div><!-- /input-group -->
            </div><!-- /.col-lg-6 -->
           
          </div><!-- /.row -->

    @else


        <a data-id="{{ $producto->id }}" class="btn btn-sm btn-success addtocart" href="{{url('cart/addtocart', [$producto->slug])}}">Agregar al carro</a>

    @endif

    <a class="btn btn-sm btn-primary vermas" href="{{ route('producto', [$producto->slug]) }}">Ver Más</a>

@endif
