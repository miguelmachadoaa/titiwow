
@extends('layouts/default')

{{-- Page title --}}
@section('title')
Carro de Productos 
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/shopping.css') }}">
    <link href="{{ asset('assets/vendors/animate/animate.min.css') }}" rel="stylesheet" type="text/css"/>
@stop

{{-- breadcrumb --}}
@section('top')
    <div class="breadcum">
        <div class="container">
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('home') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#3d3d3d" data-hc="#3d3d3d"></i>Dashboard
                    </a>
                </li>
                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#01bc8c" data-hc="#01bc8c"></i>
                    <a href="#">Carrito de Compra</a>
                </li>

                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#01bc8c" data-hc="#01bc8c"></i>
                    <a href="{{url('productos')}}">Productos</a>
                </li>
            </ol>
            <div class="pull-right">
                <i class="livicon icon3" data-name="edit" data-size="20" data-loop="true" data-c="#3d3d3d" data-hc="#3d3d3d"></i> Products
            </div>
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container text-center">
    <div class="row">
        <h1>Carro de Compras</h1>
        @if(count($cart))
            
        <a class="btn  btn-danger" href="{{url('cart/vaciar')}}">Vaciar</a>
        <br>
         <div class="col-md-10 col-md-offset-1 table-responsive">
	     <table class="table table-striped ">
                 <thead>
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
                            <td><img height="60px" src="../uploads/blog/{{$row->imagen_producto}}"></td>
                            <td>{{$row->nombre_producto}}</td>
                            <td>{{number_format($row->precio,2)}}</td>
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
                                data-href="{{url('cart/update', [$row->slug])}}" 
                                data-id="{{$row->id}}" 
                                ><i class="fa fa-refresh"></i></a>
                                

                            </td>
                            <td>{{ number_format($row->cantidad*$row->precio, 2) }}</td>
                            <td><a class="btn btn-danger" href="{{url('cart/delete', [$row->slug])}}">X</a></td>
                        </tr>
                     @endforeach

                 </tbody>
             </table>

             <hr>

             <h3><span class="label label-success">Total: {{number_format($total, 2)}}</span></h3>
         </div>
     </div>

     @else


     <h1><span class="label label-primary">No hay productos en el carro</span></h1>
        

     @endif

     <hr>
     <p>
         <a class="btn btn-default" href="{{url('productos')}}">Seguir Comprando </a>
         <a class="btn btn-default" href="{{url('order/detail')}}">Continuar</a>
     </p>
</div>
@endsection

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>
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