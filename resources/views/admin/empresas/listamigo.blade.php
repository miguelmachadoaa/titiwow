<table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Email</th>
                                    <th>Enlace</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($invitaciones as $row)
                                <tr>
                                    <td>{!! $row->id !!}</td>
                                    <td>{!! $row->nombre_amigo!!}</td>
                                    <td>{!! $row->apellido_amigo !!}</td>
                                    <td>{!! $row->email_amigo !!}</td>
                                    <td><a href=" {!! url('/').'/registroafiliado/'.$row->token  !!}  ">Enlace</a></td>
                                    <td>
                                            
                                           
                                            <a data-id="{{ $row->id }}" data-url="{{ url('admin/empresas/delamigo') }}" class="delAmigo" href="#" data-toggle="modal" >
                                            <i class="fa fa-trash"></i>
                                             </a>


                                              

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>