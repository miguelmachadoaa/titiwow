@if(isset($clientes))

 <h3>Listado de Clientes</h3>

    @if(count($clientes))

        @foreach($clientes as $c)

            <div class="row" style="padding: 0;border-bottom: 1px solid rgba(0,0,0,0.1); ">
                
                <div class="col-sm-10">
                    <p><b>{{$c->first_name.' '.$c->last_name}}</b></p>
                    <p class="">{{$c->email}}   </p>
                    
                </div>

                <div class="col-sm-2" style="padding: 0.5em;">
                    <button type="button" data-id="{{$c->id}}" class="btn btn-primary asignaCliente">Seleccionar</button>
                </div>
            </div>

        @endforeach

    @endif

@else

<p>No se encontraron resultados</p>

@endif