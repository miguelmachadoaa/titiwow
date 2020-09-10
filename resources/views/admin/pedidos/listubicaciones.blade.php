@if(count($formas_pago)>0)

    <table class="table" id="categorias_list">
        <thead>
            <tr>
            <th>Forma Envio</th>
            <th>Accion</th>
        </tr>
        </thead>
        <tbody>
            @foreach($formas_pago as $fp)

            <tr>
                <td>{{$fp->nombre_forma_pago}}</td>
                <td>
                    <button data-idcupon="{{ $fp->id_almacen }}" data-id="{{$fp->id}}" class="btn btn-danger delalmacenformapago">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>


            @endforeach
        </tbody>
        
    </table>


@else

<div class="badge badge-danger">
    No hay formas de pago asiganadas, Se usan las establecidas por Rol.
</div>

@endif