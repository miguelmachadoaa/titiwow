<div class="col-sm-12">

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

</div>




<hr>
