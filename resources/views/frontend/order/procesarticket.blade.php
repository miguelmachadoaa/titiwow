
    <div class="row text-center">

        @if($aviso_pago!='0')
        <div class="col-sm-12">
            <div class="alert  @if(isset($estatus_aviso)) alert-{{ $estatus_aviso }} @else alert-success @endif alertita" >

                Hemos procesado su orden satisfactoriamente, Su id para realizar el deposito en efectivo es <h4>{{ $payment['response']['id'] }}</h4>. Las indicaciones para finalizar su pago puede seguirlas en este enlace <a target='_blank' href='{{ $payment['response']['transaction_details']['external_resource_url'] }}' >Ticket</a>. Tiene 72 Horas para realizar el pago, o su orden sera cancelada. ¡Muchas gracias por su Compra!
                
               
            </div>
        </div>
        @endif

        <div class="col-sm-12">

            <h3>Gracias por su compra, recibirá un correo con el detalle de su pedido</h3>

            <h5>Su forma de Pago fue: <b>{{ ucwords($metodo) }}</b> </h5>

            <h5>Ha seleccionado enviar el pedido con <b>{{ $compra->nombre_forma_envios }}</b> y será entregado {{ $fecha_entrega }}</h5>

            <h5>De igual forma puede consultar la información y estatus de su pedido en su</h5> <a class="btn btn-sm btn-info" href="{{secure_url('clientes')}}">Perfil</a> 



        <div class="row">
            
        <h1>Detalle de Su Pedido</h1>
        
        <br>
         <div class="col-md-10 col-md-offset-1 table-responsive">
         <table class="table  ">
                 <thead style="border-top: 1px solid rgba(0,0,0,0.1);">
                     <tr>
                         <th>Imagen</th>
                         <th>Producto</th>
                         <th>Precio</th>
                         <th>Cantidad</th>
                         <th>SubTotal</th>
                     </tr>
                 </thead>
                 <tbody>
                     @foreach($detalles as $row)
                        <tr>
                            <td><a target="_blank"  href="{{ secure_url('producto', [$row->slug]) }}" ><img height="60px" src="../uploads/productos/{{$row->imagen_producto}}"></a></td>
                            <td><a target="_blank"  href="{{ secure_url('producto', [$row->slug]) }}" >{{$row->nombre_producto}}</a></td>
                            <td>{{number_format($row->precio_unitario,0,",",".")}}</td>
                            <td> {{ $row->cantidad }} </td>
                            <td>{{ number_format($row->precio_total, 0,",",".") }}</td>
                        </tr>
                     @endforeach

                     <tr>
                         <td colspan="4" style="text-align: right;">
                             <b>Total: </b>
                         </td>
                         <td>
                             {{number_format($compra->monto_total, 0,",",".")}}
                         </td>
                     </tr>
                     <tr>
                         <td colspan="4" style="text-align: right;">
                             <b>Base Impuesto: </b>
                         </td>
                         <td>
                             {{number_format($compra->base_impuesto, 0,",",".")}}
                         </td>
                     </tr>

                     <tr>
                         <td colspan="4" style="text-align: right;">
                             <b>Iva:</b> {{ $compra->valor_impuesto*100 }}%: 
                         </td>
                         <td>
                             {{number_format($compra->monto_impuesto, 0,",",".")}}
                         </td>
                     </tr>

                       <tr>
                         <td colspan="4" style="text-align: right;">
                             <b>Monto Ahorrado: </b>
                         </td>
                         <td>
                             {{number_format($compra->monto_total_base-$compra->monto_total, 0,",",".")}}
                         </td>
                     </tr>

                     

                 </tbody>
             </table>

             <hr>

         </div>
     </div>


     <div class="row">
         <div class="col-md-10 col-md-offset-1 table-responsive" style="padding-bottom:20px;">
             
            <a class="label label-seguir"  href="{{ secure_url('/productos') }}">Seguir Comprando <i class="fa fa-plus" aria-hidden="true"></i></a>
         </div>
     </div>



        </div>
       
    </div>
