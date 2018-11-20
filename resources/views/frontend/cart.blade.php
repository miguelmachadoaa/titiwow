
@extends('layouts/default')

{{-- Page title --}}
@section('title')
Carrito de Compras
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
                    <a href="{{ route('home') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>Dashboard
                    </a>
                </li>
                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>
                    <a href="#">Carrito de Compras</a>
                </li>

                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>
                    <a href="{{url('productos')}}">Listado</a>
                </li>
            </ol>
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container contain_body text-center" id="">

    <div class="row" id="table_detalle">

    <div class="row">

        <h1>Carrito de Compras</h1>

        <a class="btn  btn-link" href="{{url('cart/vaciar')}}">Vaciar</a>

        @if(count($cart))
                   
            <br>

            <div class="col-md-10 col-md-offset-1 table-responsive" >

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

                                <td><a target="_blank"  href="{{ route('producto', [$row->slug]) }}" ><img height="60px" src="../uploads/productos/{{$row->imagen_producto}}"></a></td>
                                <td><a target="_blank"  href="{{ route('producto', [$row->slug]) }}" >{{$row->nombre_producto}}</a></td>
                                <td>{{number_format($row->precio_oferta,0,",",".")}}</td>
                                
                                <td>
                                    <input 
                                    style="text-align: center;" 
                                    class="cantidad" 
                                    type="number" 
                                    data-id="{{$row->id}}" 
                                    data-slug="{{$row->slug}}" 
                                    data-url="{{url('cart/updatecart')}}" 
                                    data-href="{{url('cart/update', [$row->slug])}}"
                                    name="producto_{{$row->id}}"
                                    id="producto_{{$row->id}}"
                                    min="1"
                                    max="100"
                                    value="{{ $row->cantidad }}" 
                                    >

                                   
                                    

                                </td>
                                <td>{{ number_format($row->cantidad*$row->precio_oferta, 2,",",".") }}</td>
                                <td><a class="btn btn-danger" href="{{url('cart/delete', [$row->slug])}}">X</a></td>
                            </tr>
                        @endforeach
                    
                        <tr>
                            
                            <td colspan="5" style="text-align: right;">Total: </td>
                            
                            <td>{{number_format($total, 2,",",".")}} 

                                <input type="hidden" name="total_orden" id="total_orden" value="{{ $total }}">
                                <input type="hidden" name="limite_orden" id="limite_orden" value="{{ $configuracion->minimo_compra }}">

                            </td>

                         </tr>

                    </tbody>

                </table>

             <hr>

            </div>
    </div>

    <p style="text-align: center;">
        <a class="btn btn-default" href="{{url('productos')}}">Seguir Comprando </a>

         <a class="btn btn-default sendDetail" href="{{url('order/detail')}}">Continuar</a>
     </p> 

    


     @else


    <h1><span class="label label-primary">Tu Carrito está Vacio</span></h1>

        <p style="text-align: center;">
           
            <a class="btn btn-default" href="{{url('productos')}}">Seguir Comprando </a>

        </p> 

        

     @endif

     <hr>

      </div>

     <div class="row">
         
         <div class="col-sm-12">
             
             <div class="res"></div>
         </div>
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


        $(document).on('click', '.sendDetail',function(e){
            e.preventDefault();

            url=$(this).attr('href');

            if (parseInt($('#total_orden').val())>=parseInt($('#limite_orden').val())) {

                window.location=url;

            }else{

                $('.res').html('<div class="alert alert-danger" role="alert">El monto minimo de compra es de '+ $('#limite_orden').val() + '</div>');
            }
        })


        

        $('#table_detalle').on('blur', '.cantidad', function(){

            var id=$(this).data('id');

            var slug=$(this).data('slug');

            var url=$(this).data('url');

            var cantidad=$('#producto_'+id).val();

            $.post(url, { slug, id, cantidad}, function(data) {

                    $('#table_detalle').html(data);
                         
            });

          //  window.location.href=href+'/'+cantidad;
        });
    </script>
@stop