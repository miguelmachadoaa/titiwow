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
                            <td><a target="_blank" href="{{ route('producto', [$row->slug]) }}" ><img height="60px" src="{{ secure_url('/') }}/uploads/productos/{{$row->imagen_producto}}"></a></td>
                            <td><a target="_blank"  href="{{ route('producto', [$row->slug]) }}" >{{$row->nombre_producto}}</a></td>
                            <td>{{number_format($row->precio_unitario,0,",",".")}}</td>
                            <td>{{ $row->cantidad }}</td>
                            <td>{{ number_format($row->precio_total,0,",",".") }}</td>                           
                        </tr>
        

    @endforeach  
    <tr>
        <td colspan="3"></td>
        <td>Base Imponible:</td>
        <td>{{ $orden->base_impuesto }}</td>
      
    </tr>

    <tr>
        <td colspan="3"></td>
        <td>Iva {{ $orden->valor_impuesto*100 }}%:</td>
        <td>{{ $orden->monto_impuesto }}</td>
      
    </tr>

    <tr>
        <td colspan="3"></td>
        <td>Total :</td>
        <td>{{ $orden->monto_total }}</td>
      
    </tr>
    </table>

</div>

   

@endif

</div>




<hr>
