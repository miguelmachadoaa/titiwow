<div class="listaproductos ">
    <div class="row">
        
    <div class="col-sm-12">
        
        @if(count($prods))
           <table class="table table-striped" id="tableAlmacen">
                                        

                    <thead>
                        <tr>
                           
                            <th>
                                Imagen
                            </th>

                            <th>
                                Rol
                            </th>


                            <th>
                                Nombre
                            </th>
                            <th>Referencia</th>
                            <th>
                                Precio Base
                            </th>

                            <th>
                                Precio Oferta
                            </th>

                            <th>
                                Inventario
                            </th>

                            <th>
                                Disponible para la venta
                            </th>

                            <th>
                                XML
                            </th>

                            <th>
                                Acción
                            </th>
                        </tr>
                    </thead>
                

            <tbody>

            @foreach($prods as $p)

            <tr>
                <!--td>
                    <div class="checkbox">
                        <label>
                          <input 
                          class="cb " 
                          id="p_{{$p->id}}" 
                          name="p_{{$p->id}}" 
                          @if(isset($check[$p->id]))
                                {{'checked'}}
                          @endif
                          type="checkbox" > 
                        </label>
                      </div>
                </td-->

                <td>
                    <figure>
                        <img style="width: 60px;" src="{{secure_url('uploads/productos/'.$p->imagen_producto)}}" data-src="{{secure_url('uploads/productos/60/'.$p->imagen_producto)}}" alt="img">
                    </figure>
                </td>

                <td>
                    {{$p->name}}
                </td>

                <td>
                    {{$p->nombre_producto}}
                </td>
                <td>
                    {{$p->referencia_producto}}
                </td>

                <td>
                    {{$p->precio_base}}
                </td>

                <td>
                    {{$p->precio_oferta}}
                </td>


                <td>
                    @if(isset($inventario[$p->id][$almacen->id]))

                        {{$inventario[$p->id][$almacen->id]}}

                    @else

                        {{'0'}}

                    @endif
                </td>


                 <td>
                    @if($p->estado_registro==1)

                        <a href="#" class="label label-success">Si</a>

                    @else

                        <a href="#" class="label label-danger">No</a>

                    @endif
                </td>



                <td>
                    @if(isset($check[$p->id]))

                        <a href="#" class="label label-success">Activo</a>

                    @else

                        <a href="#" class="label label-danger">Inactivo</a>

                    @endif
                </td>

                <td>
                    <button data-id="{{$p->xml_id}}" type="button" class="btn btn-danger delproducto">
                        Eliminar
                    </button>
                </td>
            </tr>




            @endforeach

            </tbody>

            </table>


        @else

            <div class="alert alert-danger">
                Aun no hay productos en el listado
            </div>

        @endif 

        </div>


        <div class="col-sm-12" style="margin-top: 2em;">
            
            <a class="btn btn-danger" href="{{secure_url('admin/xml/deltodos/list')}}">
                Eliminar Todos Los Productos de la lista 
            </a>

        </div>



</div>

    </div>