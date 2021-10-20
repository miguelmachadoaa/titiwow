@if(isset($clientes))

 <h3>Listado de Clientes</h3>

    @if(count($clientes))

        @foreach($clientes as $c)

            @if($c->role_id!=1)

            <div class="row" style="padding: 0;border-bottom: 1px solid rgba(0,0,0,0.1); ">
                
                <div class="col-sm-10">
                    <p><b>{{$c->first_name.' '.$c->last_name}}</b></p>
                    <p class="">{{$c->email}}  | {{$c->telefono_cliente}} </p>
                    <p><b>Ubicación: </b>{{$c->city_name}}</p>
                    @if($c->origen=='1')
                        <p class=""> <b>Origen:</b> Tomapedidos  </p>
                    @else
                        <p class=""><b>Origen:</b>  Web  </p>
                    @endif
                    
                </div>

                <div class="col-sm-2" style="padding: 0.5em;">
                    <button type="button" data-id="{{$c->id}}" class="btn btn-success asignaCliente">Seleccionar</button>
                </div>
            </div>

            @endif

        @endforeach

    @endif

@else

<p>No se encontraron resultados</p>

@endif