@if(count($almacen_formas_envio)>0)

    <table class="table" id="categorias_list">
        <thead>
            <tr>
            <th>Forma Envio</th>
            <th>Accion</th>
        </tr>
        </thead>
        <tbody>
            @foreach($almacen_formas_envio as $fe)

            <tr>
                <td>{{$fe->nombre_forma_envio}}</td>
                <td>
                    <button data-idalmacen="{{ $fe->id_almacen }}" data-id="{{$fe->id}}" class="btn btn-danger delalmacenformaenvio">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>


            @endforeach
        </tbody>
        
    </table>


@else

<div class="badge badge-danger">
    No hay formas de envio asiganadas, Se usan las establecidas por Rol.
</div>

@endif