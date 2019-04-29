  @if(count($roles_list)>0)

                            <table class="table">
                                <thead>
                                    <tr>
                                    <th>Empresa</th>
                                    <th>Accion</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($roles_list as $rol)
            
                                    <tr>
                                        <td>{{$rol->name}}</td>
                                        <td>
                                            <button data-idcupon="{{$rol->id_cupon}}" data-id="{{$rol->id}}" class="btn btn-danger delcuponrol">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>


                                    @endforeach
                                </tbody>
                                
                            </table>


                            @else
            
                            <div class="badge badge-danger">
                                No hay Roles asignados
                            </div>

                            @endif