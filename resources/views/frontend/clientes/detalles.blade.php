<div class="col-sm-12">

@if(count($detalles))

 <div class="table-responsive">

   <table class="table table-responsive width100"  id="table">

   
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
                            <td><a target="_blank" href="{{ route('producto', [$row->slug]) }}" ><img height="60px" src="{{ url('/') }}/uploads/productos/{{$row->imagen_producto}}"></a></td>
                            <td><a target="_blank"  href="{{ route('producto', [$row->slug]) }}" >{{$row->nombre_producto}}</a></td>
                            <td>{{number_format($row->precio_unitario,2)}}</td>
                            <td>{{ $row->cantidad }}</td>
                            <td>{{ number_format($row->precio_total, 2) }}</td>                           
                        </tr>

        
   
        

    @endforeach  

    </table>

</div>

   

@endif

</div>




<hr>
