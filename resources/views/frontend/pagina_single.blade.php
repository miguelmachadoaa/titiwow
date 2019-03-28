
@extends('layouts/default')

{{-- Page title --}}
@section('title')
{{$cms->titulo_pagina}} @parent
@stop
@section('meta_tags')
<meta property="og:title" content="{{$cms->seo_titulo}} | Alpina GO!">
<meta property="og:description" content="{{$cms->seo_descripcion}}">
<meta property="og:robots" content="index, follow">
<meta property="og:revisit-after" content="3 days">
@endsection

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
                    <a href="#">Páginas</a>
                </li>
            </ol>
            
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container contain_body">
    <div class="row">
        <div class="col-md-3 hidden-xs">
        @include('layouts.sidebar')
        </div>
        <div class="col-md-9">
            <div style="padding:10px 30px; 20px 30px">
                <h3 class="text-center">{{$cms->titulo_pagina}}</h3>

                @php
                $string = $cms->texto_pagina;
                echo $string;
                @endphp
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


        

         $('body').on('click','.addtocart', function(e){

            e.preventDefault();

            base=$('#base').val();

            id=$(this).data('id');

            url=$(this).attr('href');

            $.get(url, {}, function(data) {

                $('.cartcontenido').html(data);

                //$('#detailCartModal').modal('show');

                $('#detalle_carro_front').html($('#modal_cantidad').val()+' '+'Items');

                    $.post(base+'/cart/botones', {id}, function(data) {

                        $('.boton_'+id+'').html(data);

                    });

            });



        });

        $('body').on('click','.updatecart', function(e){

            e.preventDefault();

            base=$('#base').val();

            id=$(this).data('id');

            tipo=$(this).data('tipo');

            slug=$(this).data('slug');

            cantidad=$('#cantidad_'+id+'').val();

            if (tipo=='suma') {

                cantidad=parseInt(cantidad);

                cantidad++;

            }else{

                cantidad=cantidad-1;
            }
            
                    $.post(base+'/cart/updatecartbotones', {id, slug, cantidad}, function(data) {

                        $('.boton_'+id+'').html('');
                        $('.boton_'+id+'').html(data);

                    });

        });



    </script>
@stop