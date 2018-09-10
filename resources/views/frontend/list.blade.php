
@extends('layouts/default')

{{-- Page title --}}
@section('title')
Products
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
                    <a href="#">Products</a>
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
<div class="container">
    <div class="row">
        <a href="{{url('cart/show')}}"> ir al carro </a>
        <h1>listado de productos</h1>
         <div class="col-md-10 col-md-offset-1">
	     <table class="table table-striped">
                 <thead>
                     <tr>
                         <th>Title</th>
                         <th>Slug</th>
                         <th>Agragar al carro</th>
                     </tr>
                 </thead>
                 <tbody>
                     @foreach($productos as $producto)
                        <tr>
                            <td><a href="{{ route('producto', [$producto->slug]) }}"> {{ $producto->nombre_producto }}</a></td>
                            <td>{{ $producto->slug }}</td>
                            <td><a class="btn btn-primary addtocart" href="{{url('cart/addtocart', [$producto->slug])}}">Agregar al carro</a></td>
                        </tr>
                     @endforeach
                 </tbody>
             </table>
         </div>
     </div>
</div>
@endsection

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>
    <script>
        jQuery(document).ready(function () {
            new WOW().init();
        });


        $('.addtocart').on('click', function(e){

            e.preventDefault();

            url=$(this).attr('href');

            $.get(url, {}, function(data) {

                if (data.resultado) {

                    $('#detalle_carro_front').html(data.contenido);
                         
                }

            });

        })


    </script>
@stop