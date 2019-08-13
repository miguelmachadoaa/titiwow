
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

        
        
        <div class="alert alert-default text-center">
        @if(isset($cliente))



            @if($role->role_id==12)

            <div class="col-sm-9" style="text-align: center;"><h4> <span>{{$user->first_name.' '.$user->last_name}}</span> </h4>
        <h4> Perteneces al rol: <span>{{$rol->name}}</span></h4>

                <h4>Cliente afiliado por la empresa: <span> {{ $cliente->nombre_empresa }}</span>        </h4></div>
            <div class="col-sm-3">
                <img style="width:84px;border:2px solid #f5ecec;" src='{{secure_url('uploads/empresas/'.$cliente->imagen_empresa)}}'class='img-responsive' alt='Image'>
            </div>

            @endif

            @if($role->role_id==11)

            <h4>   <span>{{$user->first_name.' '.$user->last_name}}</span> </h4>
        <h4> Perteneces al rol:  <span>{{$rol->name}}</span> </h4>

                <h4>Usted es parte de los amigos alpina de:  <span> {{ $cliente->nombre_embajador }}</span></h4>

            @endif

            @if($role->role_id==10)

            <h4>Bienvenido a AlpinaGo, ya eres un Embajador Alpina. Invita a tus Amigos y familiares para empezar a disfrutar de nuestro producto.</h4>

            <h4>   <span><{{$user->first_name.' '.$user->last_name}}/span> </h4>
        <h4> Perteneces al rol:  <span>{{$rol->name}}</span> </h4>

             

            @endif

            @if($role->role_id==9)

            <h4>   <span>{{$user->first_name.' '.$user->last_name}}</span> </h4>
        <h4> Perteneces al rol: <span> {{$rol->name}}</span> </h4>

                 <h4>Bienvenido a AlpinaGo, Ya eres un Cliente AlpinaGo.</h4>

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

        
        <div class="col-sm-12" style="margin-top: 1em;">
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


                
                <div class="col-sm-2 text-center"> 
                <a  href="{{ secure_url('convenios') }}" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class="fa fa-industry"></i></div>
                    
                        <div class="col-sm-12">Mi Convenio</div>

                    

                    </div>
                </a>
                </div> 



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





    </script>
@stop