
@extends('layouts/default')

{{-- Page title --}}
@section('title')
Area clientes   
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/shopping.css') }}">
    <link href="{{ asset('assets/vendors/animate/animate.min.css') }}" rel="stylesheet" type="text/css"/>

    <style type="text/css">
        
        .btn-large {
            height: 180px;
            line-height: 150px;
            width: 180px;
            margin: 1em;
                display: inline-block;

                 border: 1px solid rgba(0,0,0,0.1);
    background: #fff !important; 
    box-shadow: 2px 2px 2px #ddd;
    border-radius: 1em;
        }




        .btn-large {
            text-decoration: none;
            color: #000;
            background-color: #26a69a;
            text-align: center;
            letter-spacing: .5px;
            transition: .2s ease-out;
            cursor: pointer;
        }


        .btn-large i {
            font-size: 4.6rem;
        }




    </style>
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
                <li>
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#01bc8c" data-hc="#01bc8c"></i>
                    <a href="{{ url('clientes') }}">Mi Perfil </a>
                </li>

                
            </ol>
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container contain_body">
    <div class="row">

        @if(isset($cliente))

            @if($cliente->id_empresa!=0)

                <h4>Cliente afiliado por la empresa: {{ $cliente->nombre_empresa }}</h4>

            @endif

            @if($cliente->id_embajador!=0)

                <h4>Usted es parte de los amigos alpina de: {{ $cliente->nombre_embajador }}</h4>

            @endif

        @endif

        
        <div class="col-sm-12">

            <a href="{{ url('my-account') }}" class=" btn-large ">
                <div class="row">
                <div class="col-sm-12" style="height: 5em;" ><i class="fa fa-user"></i></div>
                <div class="col-sm-12">Mi Cuenta</div>
                </div>
        

            </a>
            

            <a href="{{ url('miscompras') }}" class=" btn-large ">
                <div class="row">
                <div class="col-sm-12" style="height: 5em;" ><i class="fa fa-shopping-cart"></i></div>
                <div class="col-sm-12">Mis Compras</div>
                </div>
        

            </a>

            <a href="{{ url('misdirecciones') }}" class=" btn-large ">
                <div class="row">
                <div class="col-sm-12" style="height: 5em;" ><i class="fa fa-map"></i></div>
                <div class="col-sm-12">Mi Dirección </div>
                </div>
        

            </a>

            @if (Sentinel::getUser()->hasAnyAccess(['clientes.misamigos']))
 
            <a  href="{{ url('misamigos') }}" class=" btn-large ">
                <div class="row">
                <div class="col-sm-12" style="height: 5em;" ><i class="fa fa-users"></i></div>
                <div class="col-sm-12">Amigos</div>
                </div>
        

            </a>

            @endif

            @if (Sentinel::getUser()->hasAnyAccess(['clientes.misamigos']))
 
            <a  href="{{ url('miestatus') }}" class=" btn-large ">
                <div class="row">
                <div class="col-sm-12" style="height: 5em;" ><i class="fa fa-users"></i></div>
                <div class="col-sm-12">Mi Estatus </div>
                </div>
        

            </a>

            @endif

            <a href="{{ url('productos') }}" class=" btn-large ">
                <div class="row">
                <div class="col-sm-12" style="height: 5em;" ><i class="fa fa-shopping-cart"></i></div>
                <div class="col-sm-12">Comprar </div>
                </div>
        

            </a>

            <a href="{{ url('#') }}" class=" btn-large ">
                <div class="row">
                <div class="col-sm-12" style="height: 5em;" ><i class="fa fa-question-circle"></i></div>
                <div class="col-sm-12">FAQS </div>
                </div>
        

            </a>

            <a href="{{ url('#') }}" class=" btn-large delete ">
                <div class="row">
                <div class="col-sm-12" style="height: 5em;" ><i class="fa fa-trash"></i></div>
                <div class="col-sm-12">Eliminar Cuenta </div>
                </div>
        

            </a>

           

            
            <!--@if (Sentinel::getUser()->hasAnyAccess(['clientes.amigos']))

            <a href="{{ url('clientes/amigos') }}" class=" btn-large ">
                <div class="row">
                <div class="col-sm-12" style="height: 5em;" ><i class="fa fa-send"></i></div>
                <div class="col-sm-12">Invitaciones</div>
                </div>
        

            </a>

            @endif-->

            

        </div>

    </div>

    
    
</div>




  
<!-- Modal Direccion -->
 <div class="modal fade" id="deleteModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Eliminar Cuenta</h4>
                    </div>
                    <div class="modal-body">
                        
                        <form method="POST" action="{{url('ordenes/confirmar')}}" id="aprobarOrdenForm" name="aprobarOrdenForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ url('/') }}">
                           
                            <div class="row">
                                <div class="col-sm-1"></div>
                                <div class="col-sm-10">
                                    <h3>Esta seguro que desea eliminar la cuenta?</h3>
                                </div>

                            </div>

                        </form>

                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn  btn-primary sendEliminar" >Eliminar</button>
                    </div>
                </div>
            </div>
        </div>


@endsection

<!-- Modal Direccion -->

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>
    <script>
        jQuery(document).ready(function () {
            new WOW().init();
        });


        $('.delete').on('click', function(e){

            e.preventDefault(); 

            $("#deleteModal").modal('show');


        });



         $('.sendEliminar').click(function () {

        base=$('#base').val();

        $.ajax({
            type: "POST",
            data:{ base },
            url: base+"/admin/clientes/eliminar",
                
            complete: function(datos){     

            //   location.reload();

            }
        });
    

});



    </script>
@stop