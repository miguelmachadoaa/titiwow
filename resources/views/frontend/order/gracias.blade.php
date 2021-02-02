
@extends('layouts/default')

{{-- Page title --}}
@section('title')
Carrito de Compras
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/shopping.css') }}">
    <link href="{{ secure_asset('assets/vendors/animate/animate.min.css') }}" rel="stylesheet" type="text/css"/>
@stop

{{-- breadcrumb --}}
@section('top')
    <div class="breadcum">
        <div class="container">
            <ol class="breadcrumb">
                <li>
                    <a href="{{ secure_url('/') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>Inicio
                    </a>
                </li>
                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>
                    <a href="#">Carrito de Compras</a>
                </li>
            </ol>
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container contain_body text-center" id="cartshow">
    <div class="row text-center">

        @if($aviso_pago!='0')
        <div class="col-sm-12">
            <div class="alert alert-success alertita" >


               <span class="texto_pagho">{!!$aviso_pago !!}</span> 
            </div>
        </div>
        @endif

        <div class="col-sm-12">

            @if($metodo=='ticket')

                <h3>Gracias por su compra, recibirá un correo con el detalle de su pedido</h3>

                <h5>Su forma de Pago fue: <b>{{ $compra->nombre_forma_pago }}</b> </h5>

                <h5>Ha seleccionado enviar el pedido con <b>{{ $compra->nombre_forma_envios }}</b> y se programará la entrega una vez se realice el pago.</h5>

                <h5>De igual forma puede consultar la información y estatus de su pedido en su</h5> <a class="btn btn-sm btn-info" href="{{secure_url('clientes')}}">Perfil</a> 


            @elseif($metodo=='credit_card')

             <h3>Gracias por su compra, recibirá un correo con el detalle de su pedido</h3>

                <h5>Su forma de Pago fue: <b>{{ $compra->nombre_forma_pago }}</b> </h5>

                @if($compra->id_forma_envio==1)

                    <h5>Ha seleccionado enviar el pedido con <b>{{ $compra->nombre_forma_envios }}</b>. Si quieres saber el estatus de tu pedido comunicate a la linea (+571)4238600 y al correo contaccenter@alpina.com</h5>

                @else

                <h5>Ha seleccionado enviar el pedido con <b>{{ $compra->nombre_forma_envios }}</b> y será entregado pronto.</h5>

                @endif

                


            @else

            <h3>Gracias por su compra, recibirá un correo con el detalle de su pedido</h3>

                <h5>Su forma de Pago fue: <b>{{ $compra->nombre_forma_pago }}</b> </h5>

                <h5>Ha seleccionado enviar el pedido con <b>{{ $compra->nombre_forma_envios }} y se programará la entrega una vez el pago sea efectivo</b></h5>

                <h5>De igual forma puede consultar la información y estatus de su pedido en su</h5> <a class="btn btn-sm btn-info" href="{{secure_url('clientes')}}">Perfil</a> 


            @endif


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
                            <td><a target="_blank"  href="{{ secure_url('producto', [$row->slug]) }}" ><img height="60px" src="{{secure_url('/uploads/productos/60/'.$row->imagen_producto)}}"></a></td>

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
                         <td id="totalpagar">
                             {{number_format($compra->monto_total+$envio->costo, 0,",",".")}}
                         </td>
                     </tr>
                     <tr>
                         <td colspan="4" style="text-align: right;">
                             <b>Costo Envio: </b>
                         </td>
                         <td>
                            @if($envio->costo<=0)
                                
                                {{'Gratis'}}

                            @else
                            
                                {{number_format($envio->costo, 0,",",".")}}

                            @endif
                             
                         </td>
                     </tr>
                     <tr>
                         <td colspan="4" style="text-align: right;">
                             <b>Base Impuesto: </b>
                         </td>
                         <td>
                             {{number_format($compra->base_impuesto+$envio_base, 0,",",".")}}
                         </td>
                     </tr>

                     <tr>
                         <td colspan="4" style="text-align: right;">
                             <b>Iva:</b> {{ $compra->valor_impuesto*100 }}%: 
                         </td>
                         <td>
                             {{number_format($compra->monto_impuesto+$envio_impuesto, 0,",",".")}}
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

                     @foreach($descuentos as $d)

                        <tr>
                         <td colspan="4" style="text-align: right;">
                             <b>Cupon {{$d->codigo_cupon}}: </b>
                         </td>
                         <td>
                             {{number_format($d->monto_descuento, 0,",",".")}}
                         </td>
                     </tr>

                     @endforeach

                     @if(isset($descuentosIcg))


                     @foreach($descuentosIcg as $di)

                        <tr>
                         <td colspan="4" style="text-align: right;">
                             <b>Descuento ICG: </b>
                         </td>
                         <td>
                             {{number_format($di->monto_descuento, 0,",",".")}}
                         </td>
                     </tr>

                     @endforeach

                     @endif


                     @if(isset($cupo_icg))


                     @if(!is_null($cupo_icg))


                      <tr>
                         <td colspan="4" style="text-align: right;">
                             <b>Porcentaje descuento  disponible </b>
                         </td>
                         <td>
                            @if($cupo_icg==0)
                            {{number_format(2, 0,",",".")}}%

                            @else

                            {{number_format($porcentaje_icg, 2,",",".")}}%
                            @endif
                             
                         </td>
                     </tr>


                     @endif
                     @endif

                     

                     

                 </tbody>
             </table>

             

             <hr>

         </div>
     </div>


     <div class="row">
         <div class="col-md-10 col-md-offset-1 table-responsive" style="padding-bottom:20px;">
             
            <a class="btn btn-primary"  href="{{ secure_url('/productos') }}">Seguir Comprando <i class="fa fa-plus" aria-hidden="true"></i></a>

            <br>
            <br>
            <br>

            <a class="btn btn-danger "  href="{{ secure_url('/logout') }}">Cerrar Sesión <i class="fa fa-sign-out-alt" aria-hidden="true"></i></a>


         </div>



     </div>

<table style="opacity: 0;">
                 <tr>
                     <td id="idpedido">{{$compra->id}}</td>
                 </tr>
                 <tr>
                     <td id="status">

                        @if($compra->estatus==1) {{'Recibido'}} @endif
                        @if($compra->estatus==2) {{'Confirmado'}} @endif
                        @if($compra->estatus==3) {{'Entregado'}} @endif
                        @if($compra->estatus==4) {{'Cancelado'}} @endif
                        @if($compra->estatus==5) {{'Aprobado'}} @endif
                        @if($compra->estatus==6) {{'Enviado'}} @endif
                        @if($compra->estatus==7) {{'Facturado'}} @endif
                        @if($compra->estatus==8) {{'En Espera'}} @endif



                    </td>
                 </tr>
             </table>

        </div>
       
    </div>
     
</div>

</div>
@endsection

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ secure_asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>
    <script>
        jQuery(document).ready(function () {
            new WOW().init();
        });


        $('body').on('click','.updatecartdetalle', function(e){

            e.preventDefault();

            base=$('#base').val();

            id=$(this).data('id');

            tipo=$(this).data('tipo');

            single=$('#single').val();


            slug=$(this).data('slug');

            cantidad=$('#cantidad_'+id+'').val();

            if (tipo=='suma') {

                cantidad=parseInt(cantidad);

                cantidad++;

            }else{

                cantidad=cantidad-1;
            }
            
                   $.post(base+'/cart/updatecartdetalle', {id, slug, cantidad}, function(data) {

                       
                         $('#table_detalle').html(data);


                    });

        });


         $(document).on('click','.addtocartDetail', function(e){

            $(this).fadeOut();

            e.preventDefault();

            base=$('#base').val();

            imagen=base+'/uploads/files/loader.gif';

            id=$(this).data('id');

            datasingle=$(this).data('single');

            price=$(this).data('price');

            slug=$(this).data('slug');

            single=$('#single').val();

            url=$(this).attr('href');

            //$('.boton_'+id+'').html('<img style="max-width:32px; max-height:32px;" src="'+imagen+'">');

            $.post(base+'/cart/agregardetail', {price, slug, datasingle}, function(data) {

                $('#table_detalle').html(data);

                       if (single==1) {

                            $('.vermas').remove();
                        }

            });

        });

    </script>
@stop