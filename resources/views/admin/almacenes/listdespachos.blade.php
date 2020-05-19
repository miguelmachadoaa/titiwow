@if(count($despachos)>0)

    <table class="table" id="categorias_list">
        <thead>
            <tr>
            <th>Ciudad</th>
            <th>Departamento</th>
            <th>Barrio</th>
            <th>Accion</th>
        </tr>
        </thead>
        <tbody>
            @foreach($despachos as $d)

            <tr>
                <td>{{$listaciudades[$d->id_city]}}</td>
                <td>{{$listaestados[$d->id_state]}}</td>
                <td>{{$listabarrios[$d->id_barrio]}}</td>
                <td>
                    <button data-idalmacen="{{ $d->id_almacen }}" data-id="{{$d->id}}" class="btn btn-danger delalmacendespacho">
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