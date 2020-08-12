  @if(count($productos_list)>0)

                            <table class="table">
                                <thead>
                                    <tr>
                                    <th>Producto</th>
                                    <th>Accion</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($productos_list as $producto)
            
                                    <tr>
                                        <td>{{$producto->nombre_producto}}</td>
                                        <td>
                                             @if($producto->condicion=='1')

                                                {{'Incluido '}}

                                            @else

                                                {{'Excluido '}}

                                            @endif
                                        </td>
                                        <td>
                                            <button data-idcupon="{{$producto->id_cupon}}" data-id="{{$producto->id}}" class="btn btn-danger delcuponproducto">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>


                                    @endforeach
                                </tbody>
                                
                            </table>


                            @else
            
                            <div class="badge badge-danger">
                                No hay productos asignados
                            </div>

                            @endif