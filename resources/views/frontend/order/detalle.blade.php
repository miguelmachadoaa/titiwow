 
@if(isset($cart))

  @foreach($cart as $row)

    <li>
                            <div class="row">
                                        <div class="col-sm-3">
                                              <img style="width: 30px;" src="{{ url('/').'/uploads/productos/'.$row->imagen_producto }}">
                                          </div>
                                          <div class="col-sm-9" style="font-size: 0.75em;">
                                              <p>{{ substr($row->nombre_producto, 0, 25) }}</p>
                                              <p>{{ $row->cantidad.'X'.$row->precio_oferta }}</p>
                                          </div>
                                      </div>

    </li>

  @endforeach

    <li><a href="{{ url('order/detail') }}">Proceder a pagar</a></li>

@endif
