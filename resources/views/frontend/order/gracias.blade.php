
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

            <h3>Gracias por su compra, recibirá un correo con el detalle de su pedido</h3>

            <h5>Su forma de Pago fue: <b>{{ $compra->nombre_forma_pago }}</b> </h5>

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
                            <td><a target="_blank"  href="{{ secure_url('producto', [$row->slug]) }}" ><img height="60px" src="{{secure_url('/uploads/productos/'.$row->imagen_producto)}}"></a></td>
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