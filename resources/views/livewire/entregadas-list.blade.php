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
                                <span wire:click="sortBy('monto_total')">Total</span>
                                <x-sort-icon sortField="monto_total" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </th>
                            <th>
                                <span wire:click="sortBy('nombre_almacen')">Almacen</span>
                                <x-sort-icon sortField="nombre_almacen" :sort-by="$sortBy" :sort-asc="$sortAsc" />
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
                    @if( isset($entregadas))
                        @foreach($entregadas as $entregada)
                            <tr>
                                <td>{{ $entregada->id}}</td>
                                <td>{{ $entregada->referencia }}</td>
                                <td>{{ $entregada->first_name }} {{ $entregada->last_name }}</td>
                                <td>{{ number_format($entregada->monto_total,2) }}</td>
                                <td>{{ $entregada->nombre_almacen}}</td>
                                <td>{{ $entregada->origen == 1 ? 'POS':'Web'}}</td>
                                <td><span class='label label-success' >{{ $entregada->estatus_nombre }}</span></td>
                                <td>{{ date('d/m/Y H:i:s', strtotime($entregada->created_at )) }}</td>
                                <td>                  
                                    <a class="btn btn-primary btn-xs" href="{{secure_url('/admin/ordenes/'.$entregada->id.'/detalle')}}" target='_blank'>
                                    ver detalles
                                    </a>
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
        <strong>Página {{ $entregadas->currentPage() * $entregadas->perPage() }} de {{ $entregadas->total() }}</strong>
        </div>
        <div class="col-lg-6 paginador" >
            {{ $entregadas->links() }}
        </div>
    </div>
</div>
