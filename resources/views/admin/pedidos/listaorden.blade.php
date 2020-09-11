@if(isset($cart))

 <h3>Detalle de Compra</h3>
 

<table class="table table-responsive table-striped table-bordered" id="tblorden" width="100%">
    <thead>
     <tr>
        <th>Imagen</th>
        <th>Nombre de Producto</th>
        <th>Precio</th>
        <th>Cantidad</th>
        <th>Total</th>
     </tr>
    </thead>
    <tbody>

        @foreach($cart as $p)

        <tr>
            <td><img style="width: 60px;" src="{{secure_url('uploads/productos/60/'.$p->imagen_producto)}}" alt="{{$p->nombre_producto}}"></td>
            <td>
                <p><b>{{$p->nombre_producto}}</b></p>
            </td>
            <td>{{$p->precio_base}}</td>
            <td>{{$p->cantidad}}</td>
            <td>{{$p->cantidad*$p->precio_base}}</td>
           
         </tr>

        @endforeach
  
    </tbody>

</table>

@endif