

<div class="cd-cart-container @if($cart!=NULL) @else empty @endif">
    <a href="#0" class="cd-cart-trigger">
        
        <ul class="count"> <!-- cart items count -->
            <li>@if($cart!=NULL) {{ count($cart) }}  @endif</li>
            <li>@if($cart!=NULL) {{ count($cart)+1 }} @endif</li>
        </ul> <!-- .count -->
    </a>

    <div class="cd-cart">
        <div class="wrapper">
            <header>
                <h2>Carrito</h2>
                <span class="undo">Item removed. <a href="#0">Undo</a></span>
            </header>
            
            <div class="body">
                <ul>
                    <!-- products added to the cart will be inserted here using JavaScript -->
                    @if(isset($cart))
                    
                    @foreach($cart as $row)

                    <li class="product">
                        <div class="product-image">
                            <a href="#0">
                                <img class="img-responsive" src="{{ secure_url('/').'/uploads/productos/'.$row->imagen_producto }}" alt="{{ $row->nombre_producto }}">
                            </a>
                        </div>
                        <div class="product-details">
                            <h3>
                                <a href="{{ route('producto', [$row->slug]) }}">{{ $row->nombre_producto }}</a>
                            </h3>
                            <span class="price">{{ intval($row->precio_oferta) }}</span>
                            <div class="actions">
                                <a data-id="{{ $row->id }}" data-slug="{{ $row->slug }}" href="#0" class="delete-item col-xs-3">Borrar</a>
                                    <div class="quantity col-xs-8">
                                        <label for="cd-product-{{ $row->id }}">Cantidad</label>
                                            <span class="select">
                                                <select class="cartselect" data-id="{{ $row->id }}" data-slug="{{ $row->slug }}" id="cd-product-{{ $row->id }}" name="quantity">
                                                    <option value="{{ $row->cantidad }}">{{ $row->cantidad }}</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                    <option value="10">10</option>
                                                </select>
                                            </span>
                                    </div>
                            </div>
                        </div>
                    </li>

                    @endforeach
                    @endif
                </ul>
            </div>

            <footer>
                <a style="background: #fff;" href="{{ secure_url('cart/show') }}" class="checkout btn"><em>Total - COP <span>@if(isset($cart)) {{ $total }}  @endif</span></em></a>
            </footer>
        </div>
    </div> <!-- .cd-cart -->
</div> <!-- cd-cart-container -->


