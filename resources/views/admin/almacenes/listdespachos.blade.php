@if(count($despachos)>0)

    <table class="table" id="categorias_list">
        <thead>
            <tr>
            <th>Forma Envio</th>
            <th>Accion</th>
        </tr>
        </thead>
        <tbody>
            @foreach($despachos as $d)

            <tr>
                <td>{{$d->id}}</td>
                <td>
                    <button data-idcupon="{{ $d->id_almacen }}" data-id="{{$d->id}}" class="btn btn-danger delalmacenformapago">
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