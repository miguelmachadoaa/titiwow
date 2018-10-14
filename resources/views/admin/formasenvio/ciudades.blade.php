
                    @if ($ciudades->count() >= 1)
                        <div class="table-responsive">

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Ciudad</th>
                                    <th>Dias para entrega</th>
                                    <th>Hora limite recepcion</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($ciudades as $row)
                                <tr>
                                    <td>{!! $row->id !!}</td>
                                    <td>{!! $row->state_name.' - '.$row->city_name!!}</td>
                                    <td>{!! $row->dias !!}</td>
                                    <td>{!! $row->hora !!}</td>
                                    <td>
                                            
                                            

                                        <button type="button" class=" btn btn-danger delCiudad"><i class="fa fa-trash"></i></button>

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