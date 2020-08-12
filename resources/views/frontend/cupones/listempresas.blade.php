  @if(count($empresas_list)>0)

                            <table class="table">
                                <thead>
                                    <tr>
                                    <th>Empresa</th>
                                    <th>Accion</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($empresas_list as $empresa)
            
                                    <tr>
                                        <td>{{$empresa->nombre_empresa}}</td>
                                        <td>
                                             @if($empresa->condicion=='1')

                                                {{'Incluido '}}

                                            @else

                                                {{'Excluido '}}

                                            @endif
                                        </td>
                                        <td>
                                            <button data-idcupon="{{$empresa->id_cupon}}" data-id="{{$empresa->id}}" class="btn btn-danger delcuponempresa">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>


                                    @endforeach
                                </tbody>
                                
                            </table>


                            @else
            
                            <div class="badge badge-danger">
                                No hay Empresas asignadas
                            </div>

                            @endif