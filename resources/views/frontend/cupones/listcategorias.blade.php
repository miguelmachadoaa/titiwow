@if(count($categorias_list)>0)

    <table class="table" id="categorias_list">
        <thead>
            <tr>
            <th>Categoria</th>
            <th>Accion</th>
        </tr>
        </thead>
        <tbody>
            @foreach($categorias_list as $categoria)

            <tr>
                <td>{{$categoria->nombre_categoria}}</td>
                <td>
                    <button data-idcupon="{{ $categoria->id_cupon }}" data-id="{{$categoria->id}}" class="btn btn-danger delcuponcategoria">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>


            @endforeach
        </tbody>
        
    </table>


@else

<div class="badge badge-danger">
    No hay categorias asignadas
</div>

@endif