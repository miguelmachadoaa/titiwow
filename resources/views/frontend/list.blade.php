
@extends('layouts/default')

{{-- Page title --}}
@section('title')
Productos
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
                    <a href="{{ route('home') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#3d3d3d" data-hc="#3d3d3d"></i>Inicio
                    </a>
                </li>
                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#01bc8c" data-hc="#01bc8c"></i>
                    <a href="#">Productos</a>
                </li>
            </ol>
            <div class="pull-right">
                <i class="livicon icon3" data-name="edit" data-size="20" data-loop="true" data-c="#3d3d3d" data-hc="#3d3d3d"></i> Productos
            </div>
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container contain_body">
<div class="row">
<div class="col-md-9">
    <div class="products">
        <div class="row">
        @if(!$productos->isEmpty())
            @foreach($productos as $producto)
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="productos">
                        <div class="text-align:center;">
                            <a href="{{ route('producto', [$producto->slug]) }}" ><img src="{{ url('/').'/uploads/productos/'.$producto->imagen_producto }}" class="img-responsive"></a>
                        </div>
                        <a href="{{ route('producto', [$producto->slug]) }}" ><h1>{{ $producto->nombre_producto }}</h1></a>
                        <div class="product_info">
                            <p id="precio_prod"><del class="@if($descuento==1) hidden @endif">${{ number_format($producto->precio_base, 2) }}</del>&nbsp;<span class="precio_base">${{ number_format($producto->precio_base*$descuento,2,",",".") }}</span></p>
                            <p class="product_botones">
                                <a class="btn btn-success addtocart" href="{{url('cart/addtocart', [$producto->slug])}}">Agregar al carro</a>
                                <a class="btn btn-primary" href="{{ route('producto', [$producto->slug]) }}">Ver Más</a>
                            </p>
                        </div>
                    </div>
                </div>
                @if ($loop->iteration % 3 == 0)
                    </div>
                    <div class="row">
                @endif
            @endforeach
            @else
            <div class="alert alert-danger">
                <strong>Lo Sentimos!</strong> No Existen productos en esta categoría.
            </div>
        @endif
        </div>
        <div class="row">
            <div class="col-md-12 text-right">
                {{ $productos->links() }}
            </div>
        </div>
    </div>
</div>
<div class="col-md-3">
    <h3>Categorias</h3>
</div>
</div>
</div>






        <!-- Modal Direccion -->
 <div class="modal fade" id="detailCartModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-sucess">
                        <h4 class="modal-title" id="modalLabeldanger">Producto Agregado a su carro de compras</h4>
                    </div>
                    <div class="modal-body cartcontenido">

                        

                      


                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-default" data-dismiss="modal">Continuar Comprando</button>
                        <a href="{{ url('order/detail') }}" class="btn  btn-info " >Proceder a Cancelar</a>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Direccion -->







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

               /* if (data.resultado) {

                    $('#detalle_carro_front').html(data.contenido);
                         
                }*/

                $('.cartcontenido').html(data);

                $('#detailCartModal').modal('show');

                $('#detalle_carro_front').html($('#modal_cantidad').val()+' '+'Items');

            });

            $.get(url, {}, function(data) {

                if (data.resultado) {

                    $('#detalle_carro_front').html(data.contenido);
                         
                }

            });



        })


    </script>
@stop