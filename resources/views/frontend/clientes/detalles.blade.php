<div class="col-sm-12">

@if(count($detalles))

   <table class="table table-responsive">

   
         <tr>
                         <th>Imagen</th>
                         <th>Producto</th>
                         <th>Precio</th>
                         <th>Cantidad</th>
                         <th>SubTotal</th>
                     </tr>
    
       

    @foreach($detalles as $row)
               
        <!-- Se construyen las opciones de envios -->

                        <tr>
                            <td><img height="60px" src="{{ url('/') }}/uploads/productos/{{$row->imagen_producto}}"></td>
                            <td>{{$row->nombre_producto}}</td>
                            <td>{{number_format($row->precio_unitario,2)}}</td>
                            <td>{{ $row->cantidad }}</td>
                            <td>{{ number_format($row->precio_total, 2) }}</td>                           
                        </tr>

        
   
        

    @endforeach  

    </table>

   

@endif

</div>




<hr>
