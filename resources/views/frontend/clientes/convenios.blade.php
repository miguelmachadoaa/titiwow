
@extends('layouts/default')

{{-- Page title --}}
@section('title')
Area clientes   
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/shopping.css') }}">
    <link href="{{ secure_asset('assets/vendors/animate/animate.min.css') }}" rel="stylesheet" type="text/css"/>

    <style type="text/css">
        
        .btn-medium {
            height: 160px;
            line-height: 160px;
            width: 160px;
            margin-bottom: 1em;
            display: inline-block;
            border: 1px solid rgba(0,0,0,0.1);
            background: #fff !important; 
            box-shadow: 2px 2px 2px #ddd;
            border-radius: 1em;
        }


        .btn-medium {
            text-decoration: none;
            color: #000;
            background-color: #26a69a;
            text-align: center;
            letter-spacing: .5px;
            transition: .2s ease-out;
            cursor: pointer;
        }


        .btn-medium i {
            font-size: 3.6rem;
        }

           h4 span {
            color: #007add;
        }




    </style>
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
                <li>
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#241F48" data-hc="#241F48"></i>
                    <a href="{{ secure_url('clientes') }}">Mi Perfil </a>
                </li>

                
            </ol>
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container contain_body">
    <div class="row">



         <div class="welcome">

        <h3>Mi Convenio</h3>

    </div>
    
    <hr>


        
        <div class="alert alert-default text-center">

            @if(isset($cliente))

                @if($role->role_id==12)

                <div class="col-sm-12 text-center"><h4>Cliente afiliado al convenio de la  empresa: <span>{{ $cliente->nombre_empresa }}</span>       </h4></div>

                @else

                    <h4>Actualmente no se encuentra afiliado a ningún convenio.</h4>


                @endif

               

            @endif

        </div>
       


        <div class="col-sm-12 text-center">
            
            <form class="form-inline" method="post" action="{{secure_url('postconvenios')}}">
                
                {{ csrf_field() }}

              <div class="form-group">



                <label for="exampleInputName2">Código de convenio </label>

                <input type="text" class="form-control" id="codigo" name="codigo" placeholder="Ingrese el Código Convenio" required="true">
              
              </div>

              <br>
              <br />
              <button type="button" class="btn btn-primary btn_convenio">Click para Asignar Convenio</button>
            </form>


            <div class="res">
                
            </div>


        </div>

        
      
    </div>
</div>


 <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />

<input type="hidden" name="base" id="base" value="{{ secure_url('/') }}" />


  

<div class="modal fade" id="ModalConvenido" tabindex="-2" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Error</h4>
            </div>
            <div class="modal-body">
               <h3>El código que intento utilizar no existe, por favor intente nuevamente.</h3> 
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger " data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Cerrar</span></button>
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
              </div>
            
            </div><!-- /.modal-content -->

        </div>
    </div>




@endsection

<!-- Modal Direccion -->

{{-- page level scripts --}}



{{-- Body Bottom confirm modal --}}
@section('footer_scripts')







    <script src="{{ secure_asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>
    <script>
        jQuery(document).ready(function () {
            new WOW().init();
        });


        $('.btn_convenio').on('click', function(){

        base=$('#base').val();

         _token=$('input[name="_token"]').val();

        var codigo=$('#codigo').val();

        if (codigo!='' && codigo!=undefined) {

            $.post('postconvenios', { codigo, _token}, function(data) {

                    
                    if (data==1) {

                        location.reload();

                    }else{

                        $('#ModalConvenido').modal('show');
                        $('#codigo').val('');
                    }

            });

        }else{

                $('#ModalConvenido').modal('show');
                        $('#codigo').val('');

        }
           
        });




    </script>
@stop