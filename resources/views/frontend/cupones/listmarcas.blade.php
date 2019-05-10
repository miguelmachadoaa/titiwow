@if(count($marcas_list)>0)

    <table class="table" id="marcas_list">
        <thead>
            <tr>
            <th>Marca</th>
            <th>Accion</th>
        </tr>
        </thead>
        <tbody>
            @foreach($marcas_list as $marca)

            <tr>
                <td>{{$marca->nombre_marca}}</td>
                <td>
                    <button data-idcupon="{{ $marca->id_cupon }}" data-id="{{$marca->id}}" class="btn btn-danger delcuponmarca">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>


            @endforeach
        </tbody>
        
    </table>


@else

<div class="badge badge-danger">
    No hay marcas asignadas
</div>

@endif