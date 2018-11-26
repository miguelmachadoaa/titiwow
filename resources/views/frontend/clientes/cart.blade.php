
@extends('layouts/default')

{{-- Page title --}}
@section('title')
Carro de Productos 
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
                    <a href="#">Carrito de Compra</a>
                </li>

                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>
                    <a href="{{secure_url('productos')}}">Listado</a>
                </li>
            </ol>
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container text-center ">
    <div class="row">
        <h1>Carrito de Compras</h1>
        <a class="btn  btn-link" href="{{secure_url('cart/vaciar')}}">Vaciar</a>
        @if(count($cart))
            
        
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
                         <th>Eliminar</th>
                     </tr>
                 </thead>
                 <tbody>
                     @foreach($cart as $row)
                        <tr>
                            <td><img height="60px" src="../uploads/productos/{{$row->imagen_producto}}"></td>
                            <td>{{$row->nombre_producto}}</td>
                            <td>{{number_format($row->precio_base,2)}}</td>
                            <td>
                                <input 
                                class=" " 
                                type="number" 
                                name="producto_{{$row->id}}"
                                id="producto_{{$row->id}}"
                                min="1"
                                max="100"
                                value="{{ $row->cantidad }}" 
                                >

                                <a 
                                href="#"
                                class="btn btn-warning btn-update-item" 
                                data-href="{{secure_url('cart/update', [$row->slug])}}" 
                                data-id="{{$row->id}}" 
                                ><i class="fa fa-refresh"></i></a>
                                

                            </td>
                            <td>{{ number_format($row->cantidad*$row->precio_base, 2) }}</td>
                            <td><a class="btn btn-danger" href="{{secure_url('cart/delete', [$row->slug])}}">X</a></td>
                        </tr>
                     @endforeach
                     <tr>
                         <td colspan="5" style="text-align: right;">
                             Total: 
                         </td>
                         <td>
                             {{number_format($total, 2)}}
                         </td>
                     </tr>

                 </tbody>
             </table>

             <hr>

         </div>
     </div>

    <p style="text-align: center;">
        <a class="btn btn-seguir" href="{{secure_url('productos')}}">Seguir Comprando  <i class="fa fa-plus" aria-hidden="true"></i></a>

         <a class="btn btn-cart" href="{{secure_url('order/detail')}}">Continuar <i class="fa fa-plus" aria-hidden="true"></i></a>
     </p> 


     @else


    <h1><span class="label label-primary">Tu Carrito está vacio</span></h1>

        <p style="text-align: center;">
           
            <a class="btn btn-seguir" href="{{secure_url('productos')}}">Seguir Comprando <i class="fa fa-plus" aria-hidden="true"></i></a>

        </p> 

        

     @endif

     <hr>
     
</div>
@endsection

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ secure_asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>
    <script>
        jQuery(document).ready(function () {
            new WOW().init();
        });


        $('.btn-update-item').on('click', function(e){

            e.preventDefault();

            var id=$(this).data('id');
            var href=$(this).data('href');
            var cantidad=$('#producto_'+id).val();

            window.location.href=href+'/'+cantidad;
        });
    </script>
@stop