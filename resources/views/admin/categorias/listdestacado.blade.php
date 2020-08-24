@if(count($destacados_list)>0)

    <table class="table" id="destacados_list">
        <thead>
            <tr>
            <th>Almacen</th>
            <th>Producto</th>
            <th>Accion</th>
        </tr>
        </thead>
        <tbody>
            @foreach($destacados_list as $destacado)

            <tr>
                <td>{{$destacado->nombre_almacen}}</td>
                <td>{{$destacado->nombre_producto}}</td>
                
                <td>
                    <button data-idcategoria="{{ $destacado->id_categoria}}" data-id="{{$destacado->id}}" class="btn btn-danger delCategoriaDestacada">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>


            @endforeach
        </tbody>
        
    </table>


@else

<div class="badge badge-danger">
    No hay Productos Destacados
</div>

@endif