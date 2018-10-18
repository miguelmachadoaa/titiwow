 <td>{!! $clientes->id !!}</td>
<td>{!! $clientes->first_name !!} {!! $clientes->last_name !!}</td>
<td>{!! $clientes->email !!}</td>
<td>{!! $clientes->telefono_cliente !!}</td>
<td>{!! $clientes->name_role !!}</td>
<td class="text-center">
    @if($clientes->estado_masterfile == 1)
    <span class="label label-sm label-success">Activo</span>
    @else
    <span class="label label-sm label-warning">Inactivo</span>
    @endif
</td>
<td class="text-center">
    @if($clientes->estado_registro == 1)
    <span class="label label-sm label-success">Activo</span>
    @elseif($clientes->estado_registro == 1)
    <span class="label label-sm label-warning">Inactivo</span>
    @endif
</td>
<td>{!! $clientes->created_at->diffForHumans() !!}</td>
<td>

    <div id="botones_{{ $clientes->id }}">

   

      <button type="button" data-id="{{ $clientes->id }}" class="btn btn-xs btn-primary activarUsuario" >Activar</button>
                                        <button type="button" data-id="{{ $clientes->id }}" class="btn btn-xs btn-danger rechazarUsuario" >Rechazar</button>

    </div>

    
</td>