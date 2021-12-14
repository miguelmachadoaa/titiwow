<div>
    <div class="row">
        <div class="col-lg-6">
            <div class="row">
                <div class="col-lg-4">
                    <label for="cantid">Seleccione la Cantidad de registros:</label>
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
                    @if( isset($aprobados))
                        @foreach($aprobados as $aprobado)
                            <tr>
                                <td>{{ $aprobado->id}}</td>
                                <td>{{ $aprobado->referencia }}</td>
                                <td>{{ $aprobado->first_name }} {{ $aprobado->last_name }}</td>
                                <td>{{ $aprobado->telefono_cliente}}</td>
                                <td>{{ $aprobado->forma_envio}}</td>
                                <td>@if($aprobado->id_forma_pago=='1' ||$aprobado->id_forma_pago=='7')
                                        <div class="btn btn-danger" >
                                        {{ $aprobado->forma_pago}}
                                        </div>
                                    @else
                                    {{ $aprobado->forma_pago}}
                                    @endif</td>
                                <td>{{ number_format($aprobado->monto_total,2) }}</td>
                                <td>{{ $aprobado->codigo_cupon}}</td>
                                <td>{{ $aprobado->nombre_almacen}}</td>
                                <td>{{ $aprobado->city_name }}</td>
                                <td>{{ $aprobado->origen == 1 ? 'POS':'Web'}}</td>
                                <td><span class='label label-success' >{{ $aprobado->estatus_nombre }}</span></td>
                                <td>{{ date('d/m/Y H:i:s', strtotime($aprobado->created_at )) }}</td>
                                <td>                  
                                    <a class="btn btn-primary btn-xs" href="/admin/ordenes/{{$aprobado->id}}/detalle" target='_blank'>
                                    ver detalles
                                    </a>
                                    @if($id_rol == 1 || $id_rol == 15 )
                                    <button wire:click="entregarOrden({{ $aprobado->id }})" class='btn btn-xs btn-info' > Entregar </button>
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
        <strong>Página {{ $aprobados->currentPage() * $aprobados->perPage() }} de {{ $aprobados->total() }}</strong>
        </div>
        <div class="col-lg-6 paginador" >
            {{ $aprobados->links() }}
        </div>
    </div>
</div>
