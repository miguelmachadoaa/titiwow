
@extends('layouts/default')

{{-- Page title --}}
@section('title')
Carrito de Compras
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

 <meta property="og:title" content="{{ $configuracion->seo_titulo }} | Alpina GO!">

    <meta property="og:description" content="{{ $configuracion->seo_descripcion }}">

    <meta property="og:image" content="{{ $configuracion->seo_image }}" />

    @if(isset($url))

    <link rel="canonical" href="{{$url}}" />


    <meta property="og:url" content="{{$url}}" />

    @endif


    <meta name="description" content="{{$configuracion->seo_description}}"/>



    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/shopping.css') }}">
    <link href="{{ secure_asset('assets/vendors/animate/animate.min.css') }}" rel="stylesheet" type="text/css"/>
@stop

{{-- breadcrumb --}}
@section('top')
    <div class="breadcum">
        <div class="container">
            <ol class="breadcrumb">
                <li>
                    <a href="{{ secure_url('/') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#241F48" data-hc="#241F48"></i>Inicio
                    </a>
                </li>
                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#241F48" data-hc="#241F48"></i>
                    <a href="#">Carrito de Compras</a>
                </li>
            </ol>
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container contain_body text-center" id="cartshow">

    @if(isset($user->id))



    <div class="row" id="table_detalle">


        <h3>Hola {{$user->first_name.' '.$user->last_name}}</h3>

        <h3>Gracias por registrarte en nuestra tienda y hacer parte de Alpina Go, ya puedes disfrutar de nuestros deliciosos productos.</h3>

        <p style="width: 100%; text-align: center"><a href="{{secure_url('clientes')}}" class="btn btn-primary">Ir al Área de Cliente</a></p>   
    </div>

    @else

    
    <div class="row" id="table_detalle">


        <h3>Hola!</h3>

        <h3>Gracias por registrarte en nuestra tienda y hacer parte de Alpina Go, ya puedes disfrutar de nuestros deliciosos productos.</h3>

        <p style="width: 100%; text-align: center"><a href="{{secure_url('clientes')}}" class="btn btn-primary">Ir al Área de Cliente</a></p>   
    </div>


    @endif

    <div class="row">
         
        <div class="col-sm-12">
             
            <div class="res"></div>

        </div>

    </div>










<div class="row">
        
    </div>
</div>

</div>

</div>




<div class="modal fade" id="CartModal" role="dialog" aria-labelledby="modalLabeldanger">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">MI PEDIDO</h4>
              </div>
              <div class="modal-body bodycarrito">
                
              @if(isset($cart))
              @if(is_array($cart))

                @foreach($cart as $key=>$cr)

                <div class="col-xs-12 " >

                    <div class="row productoscarritodetalle"  style="padding:0; margin:0;     border-bottom: 2px solid rgba(0,0,0,0.1);">
                        
                        <div class="col-sm-2" style="padding-top: 3%;">
                            <img style="width:100% ; max-width: 90px;" src="{{secure_url('uploads/productos/'.$cr->imagen_producto)}}"  alt="{{$cr->nombre_producto}}">
                        </div>
                        <div class="col-sm-4" style="padding-top: 3%;">
                            <p>{{$cr->nombre_producto}}</p>
                        </div>
                        
                        <div class="col-sm-2 col-xs-4" style="padding-top: 3%;">
                            <p>{{number_format($cr->precio_oferta, 0, ',', '.')}} </p>
                        </div>

                        <div class="col-sm-1 col-xs-1" style="padding-top: 3%;">
                            <p>{{$cr->cantidad}} </p>
                        </div>


                        <div class="col-sm-2 col-xs-4" style="padding-top: 3%; ">
                            <p>{{number_format($cr->precio_oferta*$cr->cantidad, 0, ',', '.')}} </p>
                        </div>

                        <div class="col-sm-1 col-xs-2" style="padding-left:0; padding-right:0; padding-top: 3%;     text-align: right; ">
                            <a data-id="{{ $cr->slug}}" data-slug="{{ $cr->slug}}"  href="#0" class="delete-item">
                                <img style="width:32px; padding-right:0; margin-bottom: 10px;" src="{{secure_url('assets/images/borrar.png')}}" alt="">
                            </a>
                        </div>

                    </div>

                </div>

                @endforeach

                @endif
                @endif
              </div>
            
            </div><!-- /.modal-content -->

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

                      /* if (single==1) {

                            $('.vermas').remove();
                        }*/

            });

        });


         setInterval(function(){

            if ( $(".nodisponible").length ) {

                console.log('hay');
              
                $('.sendDetail').attr('disabled','')

            }else{

                $('.sendDetail').removeAttr('disabled')

            }

         }, 1000);

        

        
    </script>
@stop