
                    @if ($ciudades->count() >= 1)
                        <div class="table-responsive">

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Rol</th>
                                    <th>Ciudad</th>
                                    
                                    @if($formas->tipo==0)

                                        <th>Compras Desde</th>
                                        <th>Compras Hasta</th>
                                        <th> Se entrega el Dia</th>

                                    @else

                                         <th>Dias para entrega</th>

                                    @endif
                                    <th>Hora limite recepción</th>
                                    <th>Costo</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($ciudades as $row)
                                <tr>
                                    <td>{!! $row->id !!}</td>
                                    <td>{!! $row->name !!}</td>
                                    <td>{!! $listaestados[$row->id_state].' - '.$listaciudades[$row->id_ciudad]!!}</td>
                                     @if($formas->tipo==0)

                                    <td>{!! $row->desde !!}</td>
                                    <td>{!! $row->hasta !!}</td>
                                    <td>{!! $row->dias !!}</td>

                                    @else

                                     <td>{!! $row->dias !!}</td>

                                    @endif
                                    <td>{!! $row->hora !!}</td>
                                    <td>{!! number_format($row->costo, 2)  !!}</td>
                                    <td>

                                        <button data-id="{{ $row->id }}" type="button" class=" btn btn-danger delciudad"><i class="fa fa-trash"></i></button>

                                            <!-- let's not delete 'Admin' group by accident -->

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    @else
                        No se encontraron registros
                    @endif   