@if(count($almacenes_list)>0)

    <table class="table" id="categorias_list">
        <thead>
            <tr>
            <th>Almacen</th>
            <th>Accion</th>
        </tr>
        </thead>
        <tbody>
            @foreach($almacenes_list as $almacen)

            <tr>
                <td>{{$almacen->nombre_almacen}}</td>
                <td>
                     @if($almacen->condicion=='1')

                        {{'Incluido '}}

                    @else

                        {{'Excluido '}}

                    @endif
                </td>
                <td>
                    <button data-idcupon="{{ $almacen->id_cupon }}" data-id="{{$almacen->id}}" class="btn btn-danger delcuponalmacen">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>


            @endforeach
        </tbody>
        
    </table>


@else

<div class="badge badge-danger">
    No hay Almacenes asignados
</div>

@endif