<div class="col-sm-12">
                    
                    <div class="">

                        @if(count($cartancheta))

                            <h3>Productos Seleccionados</h3>

                            @foreach($cartancheta as $carta)

                                <h6> <i class="fa fa-angle-double-right"></i>{{$carta->nombre_producto}}</h6>

                            @endforeach

                        @endif

                        <h3>Total de la Ancheta : <span class="totalancheta">{{$total}}</span></h3>

                    </div>

                </div>