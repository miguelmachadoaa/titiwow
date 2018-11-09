

    <input type="hidden" name="cantidad" id="cantidad" value="{{ $cantidad }}">
    <input type="hidden" name="limite" id="limite" value="{{ $configuracion->limite_amigos }}">

    <h3>Solo te quedan {{ $configuracion->limite_amigos-$cantidad }} invitaciones disponibles por enviar</h3>


@if(count($amigos))



    <div class="form-group col-sm-10 col-sm-offset-1">


               
         <table class="table table-responsive">
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                        <th>Creado</th>
                        <th>Acciones</th>
                    </tr>


            @foreach($amigos as $row)

                    <tr>
                        <td>
                            {{ $row->nombre_amigo }}
                        </td>
                        <td>
                            {{ $row->apellido_amigo }}
                        </td>
                        <td>
                            {{ $row->email_amigo }}
                        </td>
                        <td>
                            {{ $row->created_at }}
                        </td>

                        

                        <td>    
                                <button data-id="{{ $row->id }}" data-url="{{ url('delamigo') }}"  class="btn btn-danger delAmigo">Eliminar</button>

                        </td>
                    </tr>
                
            @endforeach
             </table>

    </div>

@endif


    <div class="form-group col-sm-10 col-sm-offset-1">
        <div class="alert alert-{{ $mensaje['tipo'] }}">{{ $mensaje['mensaje'] }}</div>
    </div>

<hr>
