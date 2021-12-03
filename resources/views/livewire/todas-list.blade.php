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
                    @if( isset($todas))
                        @foreach($todas as $row)
                            <tr>
                                <td>{{ $row->id}}</td>
                                <td>{{ $row->referencia }}</td>
                                <td>{{ $row->first_name }} {{ $row->last_name }}</td>
                                <td>{{ $row->telefono_cliente}}</td>
                                <td>{{ $row->forma_envio}}</td>
                                <td>{{ $row->forma_pago}}</td-->
                                <td>{{ number_format($row->monto_total,2) }}</td>
                                <td>{{ $row->codigo_cupon}}</td>
                                <td>{{ $row->nombre_almacen}}</td>
                                <td>{{ $row->city_name }}</td>
                                <td>{{ $row->origen == 1 ? 'POS':'Web'}}</td>
                                @if($row->estatus == 1)
                                <td><span class='label label-primary' >{{ $row->estatus_nombre }}</span></td>
                                @elseif($row->estatus == 5)
                                <td><span class='label label-info' >{{ $row->estatus_nombre }}</span></td>
                                @elseif($row->estatus == 8)
                                <td><span class='label label-warning' >{{ $row->estatus_nombre }}</span></td>
                                @elseif($row->estatus == 3)
                                <td><span class='label label-success' >{{ $row->estatus_nombre }}</span></td>
                                @elseif($row->estatus == 4)
                                <td><span class='label label-danger' >{{ $row->estatus_nombre }}</span></td>
                                @elseif($row->estatus == 7)
                                <td><span class='label label-default' >{{ $row->estatus_nombre }}</span></td>
                                @endif
                                <td>{{ date('d/m/Y H:i:s', strtotime($row->created_at )) }}</td>
                                <td>                  
                                    <a class="btn btn-primary btn-xs" href="/admin/ordenes/{{$row->id}}/detalle" target='_blank'>
                                    ver detalles
                                    </a>

                                    @if($row->estatus == 8 )

                                    <a  class="btn btn-danger btn-xs" href="/admin/ordenes/{{$row->id}}/cancelarorden" target='_blank'>
                                    Cancelar Orden
                                    </a>

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
        <strong> {{ $todas->currentPage() * $todas->perPage() }} Registros de {{ $todas->total() }}</strong>
        </div>
        <div class="col-lg-6 paginador" >
            {{ $todas->links() }}
        </div>
    </div>
</div>
