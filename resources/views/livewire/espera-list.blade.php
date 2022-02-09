<div>
    <div class="row">

        <div class="col-lg-6">
            <div class="row">
                <div class="col-lg-4">
                    <label for="cantid">Seleccione la Cantidad de registros: {{$idCancelar}}</label>
                </div>
                <div class="col-lg-2">
                    <select wire:model="cantid" class="form-control">
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                    <br />
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <input wire:model="search" placeholder="Buscar ..." type="text" class="form-control">
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-lg-12">
            <div class=" table-responsive-lg table-responsive-md table-responsive-sm table-responsive ">

                <table class="table table-striped" id="table">
                    <thead>
                        <tr id="miTabla">
                            <th>
                                <span wire:click="sortBy('id')" stye="border:none;">ID</span>
                                <x-sort-icon sortField="id" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </th>
                            <th>
                                <span wire:click="sortBy('referencia')">Referencia</span>
                                <x-sort-icon sortField="referencia" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </th>
                            <th>
                                <span wire:click="sortBy('cliente')">Cliente</span>
                                <x-sort-icon sortField="cliente" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </th>
                            <th>
                                <span wire:click="sortBy('telefono_cliente')">Teléfono</span>
                                <x-sort-icon sortField="telefono_cliente" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </th>
                            <th>
                                <span wire:click="sortBy('forma_envio')">Forma de Envio</span>
                                <x-sort-icon sortField="forma_envio" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </th>
                            <th>
                                <span wire:click="sortBy('forma_pago')">Forma de Pago</span>
                                <x-sort-icon sortField="forma_pago" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </th>
                            <th>
                                <span wire:click="sortBy('monto_total')">Total</span>
                                <x-sort-icon sortField="monto_total" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </th>
                            <th>
                                <span wire:click="sortBy('referencia')">Cupón</span>
                                <x-sort-icon sortField="referencia" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </th>
                            <th>
                                <span wire:click="sortBy('nombre_almacen')">Almacen</span>
                                <x-sort-icon sortField="nombre_almacen" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </th>
                            <th>
                                <span wire:click="sortBy('city_name')">Ciudad</span>
                                <x-sort-icon sortField="city_name" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </th>
                            <th>
                                <span wire:click="sortBy('origen')">Origen</span>
                                <x-sort-icon sortField="origen" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </th>
                            <th>
                                <span wire:click="sortBy('estatus')">Estado</span>
                                <x-sort-icon sortField="estatus" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </th>
                            <th>
                                <span wire:click="sortBy('created_at')">Creado</span>
                                <x-sort-icon sortField="created_at" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if( isset($enespera))
                        @foreach($enespera as $espera)
                            <tr>
                                <td>{{ $espera->id}}</td>
                                <td>{{ $espera->referencia }}</td>
                                <td>{{ $espera->first_name }} {{ $espera->last_name }}</td>
                                <td>{{ $espera->telefono_cliente}}</td>
                                <td>{{ $espera->forma_envio}}</td>
                                <td>
                                    @if($espera->id_forma_pago=='1' ||$espera->id_forma_pago=='7')
                                    <span class='label label-danger' >
                                        {{ $espera->forma_pago}}
                                    </span>
                                    @else
                                    {{ $espera->forma_pago}}
                                    @endif
                                    
                                </td>
                                <td>{{ number_format($espera->monto_total,2) }}</td>
                                <td>{{ $espera->codigo_cupon}}</td>
                                <td>{{ $espera->nombre_almacen}}</td>
                                <td>{{ $espera->city_name }}</td>
                                <td>{{ $espera->origen == 1 ? 'POS':'Web'}}</td>
                                <td><span class='label label-success' >{{ $espera->estatus_nombre }}</span></td>
                                <td>{{ date('d/m/Y H:i:s', strtotime($espera->created_at )) }}</td>
                                <td>                  
                                    <a class="btn btn-primary btn-xs"href="{{secure_url('/admin/ordenes/'.$espera->id.'/detalle')}}" target='_blank'>
                                    ver detalles
                                    </a>

                                    @if($espera->estatus_pago=='4' || $espera->estatus_pago=='1')

                                        <button data-toggle="modal" data-target="#exampleModal" class="btn btn-danger btn-xs btnCancelar" wire:click="selectedcancelar({{ $espera->id }})" data-id="{{$espera->id}}"> Cancelar</button>

                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>No existen Datos</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6" >
        <strong>Página {{ $enespera->currentPage() * $enespera->perPage() }} de {{ $enespera->total() }}</strong>
        </div>
        <div class="col-lg-6 paginador" >
            {{ $enespera->links() }}
        </div>
    </div>



    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cancelar Orden</h5>
                <button type="button" class="btn-close btn-link" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <h3>Seguro que desea cancelar la orden?</h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" wire:click="cancelar()">Cancelar Orden</button>
            </div>
            </div>
        </div>
        </div>


</div>
