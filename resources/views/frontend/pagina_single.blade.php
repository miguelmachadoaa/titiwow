
@extends('layouts/default')

{{-- Page title --}}
@section('title')
{{$cms->titulo_pagina}} @parent
@stop
@section('meta_tags')

<link rel="canonical" href="{{$url}}" />

<meta property="og:title" content="{{ $cms->seo_title}} | Alpina GO!">

<meta property="og:image" content="{{$configuracion->seo_image}}" />

<meta property="og:url" content="{{$url}}" />

<meta property="og:description" content="{{$cms->seo_description}}">

<link rel="canonical" href="{{$url}}" />

<meta name="description" content="{{$cms->seo_description}}"/>

@if(isset($cms->robots))

    @if($cms->robots==null)

    @else

        <meta name="robots" content="{{$cms->robots}}">
        
    @endif

@else

    <meta name="robots" content="index, follow">
    
@endif


@if(isset($configuracion->cuenta_twitter))
          <meta name="twitter:card" content="summary">
<meta name="twitter:site" content="{{'@'.$configuracion->cuenta_twitter}}">
<meta name="twitter:description" content="{{$configuracion->seo_description}}">
<meta name="twitter:title" content="{{ $configuracion->seo_title}}">
<meta name="twitter:image" content="{{$configuracion->seo_url}}">
@endif


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
                    <a href="{{ secure_url('/') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#241F48" data-hc="#241F48"></i>Inicio
                    </a>
                </li>
                <li class="hidden-md hidden-lg">
                    <a href="{{ secure_url('/') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#241F48" data-hc="#241F48"></i>
                    </a>
                </li>
                <li>
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#241F48" data-hc="#241F48"></i>
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

    <script type="text/javascript">

        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
        'event': 'visitaPagina',
        'pageTitle': '{{$cms->titulo_pagina}}'
        });

    </script>

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P4Q89NF"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    
@stop