  @if(count($clientes_list)>0)

                            <table class="table">
                                <thead>
                                    <tr>
                                    <th>Empresa</th>
                                    <th>Accion</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($clientes_list as $cliente)
            
                                    <tr>
                                        <td>{{$cliente->first_name.' '.$cliente->last_name}}</td>
                                        <td>
                                            <button data-idcupon="{{$cliente->id_cupon}}" data-id="{{$cliente->id}}" class="btn btn-danger delcuponcliente">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>


                                    @endforeach
                                </tbody>
                                
                            </table>


                            @else
            
                            <div class="badge badge-danger">
                                No hay Clientes asignados
                            </div>

                            @endif