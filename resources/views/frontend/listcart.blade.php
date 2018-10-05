    <div class="row">

        <h1>Carro de Compras</h1>

        <a class="btn  btn-link" href="{{url('cart/vaciar')}}">Vaciar</a>

        @if(count($cart))
                   
            <br>

            <div class="col-md-10 col-md-offset-1 table-responsive" >

                <table class="table  ">

                    <thead style="border-top: 1px solid rgba(0,0,0,0.1);">

                        <tr>

                            <th>Imagen</th>

                            <th>Producto</th>

                            <th>Precio</th>

                            <th>Cantidad</th>

                            <th>SubTotal</th>

                            <th>Eliminar</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($cart as $row)

                            <tr>

                                <td><img height="60px" src="../uploads/productos/{{$row->imagen_producto}}"></td>
                                <td>{{$row->nombre_producto}}</td>
                                <td>{{number_format($row->precio_base,2,",",".")}}</td>
                                <td>
                                    <input 
                                    style="text-align: center;" 
                                    class="cantidad" 
                                    type="number" 
                                    data-id="{{$row->id}}" 
                                    data-slug="{{$row->slug}}" 
                                    data-url="{{url('cart/updatecart')}}" 
                                    data-href="{{url('cart/update', [$row->slug])}}"
                                    name="producto_{{$row->id}}"
                                    id="producto_{{$row->id}}"
                                    min="1"
                                    max="100"
                                    value="{{ $row->cantidad }}" 
                                    >

                                    

                                </td>
                                <td>{{ number_format($row->cantidad*$row->precio_base, 2,",",".") }}</td>
                                <td><a class="btn btn-danger" href="{{url('cart/delete', [$row->slug])}}">X</a></td>
                            </tr>
                        @endforeach
                    
                        <tr>
                            
                            <td colspan="5" style="text-align: right;">Total: </td>
                            
                            <td>{{number_format($total, 2,",",".")}}</td>

                         </tr>

                    </tbody>

                </table>

             <hr>

            </div>
    </div>

    <p style="text-align: center;">
        <a class="btn btn-default" href="{{url('productos')}}">Seguir Comprando </a>

         <a class="btn btn-default" href="{{url('order/detail')}}">Continuar</a>
     </p> 


     @else


    <h1><span class="label label-primary">No hay productos en el carro</span></h1>

        <p style="text-align: center;">
           
            <a class="btn btn-default" href="{{url('productos')}}">Seguir Comprando </a>

        </p> 

        

     @endif

     <hr>