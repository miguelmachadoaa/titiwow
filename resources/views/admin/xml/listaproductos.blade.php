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