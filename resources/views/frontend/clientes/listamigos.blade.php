@if(!$referidos->isEmpty())
                <div class="form-group col-sm-10 col-sm-offset-1 table-responsive">
                    <table class="table table-responsive" id="tbAmigos">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Email</th>
                                <th>Puntos</th>
                                <th>Creado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>

                        @foreach($referidos as $referido)

                                <tr>
                                    <td>
                                        {{ $referido->first_name }}
                                    </td>
                                    <td>
                                        {{ $referido->last_name }}
                                    </td>
                                    <td>
                                        {{ $referido->email }}
                                    </td>
                                    <td>
                                        {{ number_format($referido->puntos,0,",",".")  }}
                                    </td>
                                    <td>
                                        {{ $referido->created_at }}
                                    </td>

                                    <td>    

                                            <a class="btn btn-xs" href="{{ secure_url('clientes/'.$referido->id_user_client.'/compras') }}">
                                                <i class="livicon" data-name="eye" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="Ver Compras"></i>
                                            </a>

                                            <button class="btn btn-xs btn-danger eliminarAmigo" data-id="{{ $referido->id_user_client }}"> <i class="fa fa-trash"> </i>  </button>


                                    </td>
                                </tr>
                            
                        @endforeach
                        </tbody>
                    </table>
                </div>

            @else
                <div class="alert alert-danger">
                    <strong>Lo Sentimos!</strong> No Existen Referidos aún.
                </div>
            @endif