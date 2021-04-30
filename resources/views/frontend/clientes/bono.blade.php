
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

        <h3>Redimir Tarjeta de Regalo</h3>

    </div>
    
    <hr>


        
        <div class="alert alert-default text-center">

            @if(isset($cliente))

                @if(isset($disponible))

                    <h4>Actualmente su bono disponible es de {{number_format($disponible->total,0,',','.')}}.</h4>

                @else

                    <h4>Actualmente su bono disponible es de 0.</h4>

                @endif

            @endif

        </div>
       


        <div class="col-sm-12 text-center">
            
            <form class="form" method="post" action="{{secure_url('postbono')}}">
                
                {{ csrf_field() }}


                <div class="form-group col-xs-12 col-sm-4 col-sm-offset-4">
                    
                    <input type="text" class="form-control col-xs-12" id="codigo" name="codigo" placeholder="Ingrese la Tarjeta de regalo" required="true" style=" margin-bottom: 2em;">

                </div>


                <div class="form-group col-xs-12 col-sm-4 col-sm-offset-4">
                    <br />
                    <button type="button" class="btn btn-primary btn_bono">Click para redimir</button>
                </div>


                

              
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


        $('.btn_bono').on('click', function(){

        base=$('#base').val();

         _token=$('input[name="_token"]').val();

        var codigo=$('#codigo').val();

        if (codigo!='' && codigo!=undefined) {

            $.post('postbono', { codigo, _token}, function(data) {

                    
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