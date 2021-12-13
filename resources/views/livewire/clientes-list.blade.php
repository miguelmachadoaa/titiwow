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
                                <span>Cliente</span>
                            </th>
                            <th>
                                <span wire:click="sortBy('telefono_cliente')">Teléfono</span>
                                <x-sort-icon sortField="telefono_cliente" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </th>
                            <th>
                                <span wire:click="sortBy('email')">Email</span>
                                <x-sort-icon sortField="email" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </th>
                            <th>
                                <span wire:click="sortBy('name_role')">Tipo Cliente</span>
                                <x-sort-icon sortField="name_role" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </th>
                            <th>
                                <span wire:click="sortBy('marketing_sms')">Marketing SMS</span>
                                <x-sort-icon sortField="marketing_sms" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </th>
                            <th>
                                <span wire:click="sortBy('marketing_email')">Marketing Email</span>
                                <x-sort-icon sortField="marketing_email" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </th>
                            <th>
                                <span wire:click="sortBy('cliente_deleted_at')">Cliente Eliminado</span>
                                <x-sort-icon sortField="cliente_deleted_at" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </th>
                            <th>
                                <span wire:click="sortBy('estado_registro')">Estado</span>
                                <x-sort-icon sortField="estado_registro" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </th>
                            <th>
                                <span wire:click="sortBy('created_at')">Creado</span>
                                <x-sort-icon sortField="created_at" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if( isset($clientes))
                        @foreach($clientes as $cliente)
                            <tr>
                                <td>{{ $cliente->id}}</td>
                                <td>{{ $cliente->first_name }} {{ $cliente->last_name }}</td>
                                <td>{{ $cliente->telefono_cliente}}</td>
                                <td>{{ $cliente->email}}</td>
                                <td>{{ $cliente->name_role }}</td>
                                <td>{{ $cliente->marketing_sms == 1 ? 'Activo':'Inactivo'}}</td>
                                <td>{{ $cliente->marketing_email == 1 ? 'Activo':'Inactivo'}}</td>
                                <td>{{ $cliente->cliente_deleted_at == null ? 'N/A':date('d/m/Y H:i:s', strtotime($cliente->cliente_deleted_at )) }}</td>
                                <td>{{ $cliente->estado_registro == 1 ? 'Activo':'Inactivo'}}</td>
                                <td>{{ date('d/m/Y H:i:s', strtotime($cliente->created_at )) }}</td>
                                <td>                
                                    <a class="btn btn-info btn-xs" href="/admin/clientes/{{$cliente->id}}/detalle" target='_blank' title="Ver Detalles" alt="Ver Detalles">
                                    <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                                    </a>
                                    <a class="btn btn-primary btn-xs" href="/admin/clientes/{{$cliente->id}}/direcciones" target='_blank' title="Ver Direcciones" alt="Ver Direcciones">
                                    <span class="glyphicon glyphicon-pushpin" aria-hidden="true"></span>
                                    </a>

                                    @if($id_rol == 1 || $id_rol == 13 )
                                    <a class="btn btn-warning btn-xs" href="/admin/clientes/{{$cliente->id}}/abono" target='_blank' title="Aplicar Bono" alt="Aplicar Bono">
                                    <span class="glyphicon glyphicon-usd" aria-hidden="true"></span>
                                    </a>
                                    <a class="btn btn-success btn-xs" href="/admin/clientes/{{$cliente->id}}/edit" target='_blank' title="Editar" alt="Editar">
                                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
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
        <strong>{{ $clientes->currentPage() * $clientes->perPage() }} Registros de {{ $clientes->total() }}</strong>
        </div>
        <div class="col-lg-6 paginador" >
            {{ $clientes->links() }}
        </div>
    </div>
</div>
