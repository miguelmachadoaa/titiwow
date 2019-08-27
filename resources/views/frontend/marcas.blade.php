
@extends('layouts/default')

{{-- Page title --}}
@section('title')
@foreach($marcaname as $marca)
{{ $marca->nombre_marca }}
@endforeach @parent
@stop
@section('meta_tags')
@php 
    foreach($marcaname as $marca)
        $marcago = $marca->seo_titulo;
        $marcades = $marca->seo_descripcion
@endphp
<meta property="og:title" content="{{$marcago}} | Alpina GO!">
<meta property="og:description" content="{{$marcades}}">
<meta property="og:robots" content="index, follow">
<meta property="og:revisit-after" content="3 days">
@endsection

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/cart.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/shopping.css') }}">
    <link href="{{ secure_asset('assets/vendors/animate/animate.min.css') }}" rel="stylesheet" type="text/css"/>
@stop

{{-- breadcrumb --}}
@section('top')
    <div class="breadcum">
        <div class="container">
            <ol class="breadcrumb">
                <li class="hidden-xs">
                    <a href="{{ secure_url('/') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>Inicio
                    </a>
                </li>
                <li class="hidden-md hidden-lg">
                    <a href="{{ secure_url('/') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>
                    </a>
                </li>
                <li>
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>
                    <a href="#">Marcas</a>
                </li>
                <li >
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>
                    @foreach($marcaname as $marca)
                        <a href="{{ $marca->slug }} ">{{ $marca->nombre_marca }} </a>
                    @endforeach
                   
                </li>
            </ol>
            
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container contain_body">
<div class="row">
<div class="col-md-3 hidden-xs hidden-sm" style="padding-right:30px">
@include('layouts.sidebar')
</div>
<div class="col-md-9">
    <div class="products">
        <div class="row">
        @if(!$productos->isEmpty())
            @foreach($prods as $producto)
                
                @include('frontend.producto')


                @if ($loop->iteration % 4 == 0)
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

      @include('frontend.includes.paginador')

       
    </div>
    </div>

</div>
</div>







        <!-- Modal Direccion -->
 <div class="modal fade" id="detailCartModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-sucess">
                        <h4 class="modal-title" id="modalLabeldanger">Producto Agregado a tu carrito de compras</h4>
                    </div>
                    <div class="modal-body cartcontenido">

                        

                      


                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-default" data-dismiss="modal">Continuar Comprando</button>
                        <a href="{{ secure_url('cart/show') }}" class="btn  btn-info " >Proceder a Pagar</a>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Direccion -->
@include('frontend.includes.newcart')



@endsection

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ secure_asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ secure_asset('assets/js/cart.js') }}"></script>

    <script>
        jQuery(document).ready(function () {
            new WOW().init();
        });


        

    </script>
@stop