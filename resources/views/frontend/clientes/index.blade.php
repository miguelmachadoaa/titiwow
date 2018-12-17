
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




    </style>
@stop

{{-- breadcrumb --}}
@section('top')
    <div class="breadcum">
        <div class="container">
            <ol class="breadcrumb">
                <li>
                    <a href="{{ secure_url('/') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>Inicio
                    </a>
                </li>
                <li>
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>
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

        
        
        <div class="alert alert-default">
        @if(isset($cliente))

            @if($cliente->id_empresa!=0)

                <h4>Cliente afiliado por la empresa: {{ $cliente->nombre_empresa }}</h4>

            @endif

            @if($cliente->id_embajador!=0)

                <h4>Usted es parte de los amigos alpina de: {{ $cliente->nombre_embajador }}</h4>

            @endif

            @if($cliente->cod_alpinista!=0)

             <h4>Bienvenido a AlpinaGo, ya eres un Embajador Alpina. Invita a tus Amigos y familiares para empezar a disfrutar de nuestro producto.</h4>

            @endif

        @else

            <h4>Bienvenido a Tu Perfil</h4>

        @endif

        </div>
        <!--@if(isset($puntos['nivel']))

            <div class="alert alert-default">
                
                <h4>Usted acumula compras este mes por  {{ $puntos['puntos'] }} COP</h4>

                <h4>Lo que lo ubica en el nivel {{ $puntos['nivel'] }} con una comision de {{ $puntos['porcentaje']*100 }}%</h4>

                <h4>Usted acumula una comision ganada de  {{ $puntos['puntos']*$puntos['porcentaje'] }} COP</h4>
            </div>

        @endif-->

        
        <div class="col-sm-12">
            <div class="row">
            <div class="col-sm-2 text-center">
                <a href="{{ secure_url('my-account') }}" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class="fa fa-user"></i></div>
                    <div class="col-sm-12">Mi Cuenta</div>
                    </div>
            

                </a>
            </div>    
            <div class="col-sm-2 text-center">
                <a href="{{ secure_url('miscompras') }}" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class="fa fa-shopping-cart"></i></div>
                    <div class="col-sm-12">Mis Compras</div>
                    </div>
            

                </a>
            </div>    
            <div class="col-sm-2 text-center">
                <a href="{{ secure_url('misdirecciones') }}" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class="fa fa-map"></i></div>
                    <div class="col-sm-12">Mi Dirección </div>
                    </div>
                </a>
            </div> 
            <div class="col-sm-2 text-center">   
                <a href="{{ secure_url('productos') }}" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class="fa fa-shopping-cart"></i></div>
                    <div class="col-sm-12">Comprar </div>
                    </div>
                </a>
            </div> 
                @if (Sentinel::getUser()->hasAnyAccess(['clientes.misamigos']))
                <div class="col-sm-2 text-center"> 
                <a  href="{{ secure_url('misamigos') }}" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class="fa fa-users"></i></div>
                    <div class="col-sm-12">Mis Amigos</div>
                    </div>
                </a>
                </div> 
                @endif

                @if (Sentinel::getUser()->hasAnyAccess(['clientes.misamigos']))
                <!--div class="col-sm-2 text-center"> 
                <a  href="{{ secure_url('miestatus') }}" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class="fa fa-users"></i></div>
                    <div class="col-sm-12">Mi Estatus </div>
                    </div>
                </a>
                </div--> 
                </div> 
            <div class="row">
                @endif
                <div class="col-sm-2 text-center"> 
                <a href="{{ secure_url('paginas/faqs') }}" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class="fa fa-question-circle"></i></div>
                    <div class="col-sm-12">Preguntas</div>
                    </div>
                </a>
                </div> 
                @if(empty($cliente->cod_alpinista))
                    <div class="col-sm-2 text-center"> 
                    <a href="{{ secure_url('#') }}" class=" btn-medium delete cajita">
                        <div class="row">
                        <div class="col-sm-12" style="height: 2em;" ><i class="fa fa-trash"></i></div>
                        <div class="col-sm-12">Eliminar Cuenta </div>
                        </div>
                    </a>
                    </div> 
                @endif
            </div>
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
                        
                        <form method="POST" action="{{secure_url('ordenes/confirmar')}}" id="aprobarOrdenForm" name="aprobarOrdenForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">
                           
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
    <script src="{{ secure_asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>
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
            type: "GET",
            url: base+"/admin/clientes/delcliente",
                
            complete: function(datos){ 

               // alert(datos);    

                $("#deleteModal").modal('hide');
                

            }
        });
    

});



    </script>
@stop