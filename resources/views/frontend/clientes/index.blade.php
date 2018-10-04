
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
                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#01bc8c" data-hc="#01bc8c"></i>
                    <a href="{{ url('clientes') }}">Area Cliente </a>
                </li>

                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#01bc8c" data-hc="#01bc8c"></i>
                    <a href="{{ url('clientes/miscompras') }}">Mis Compras </a>
                </li>
            </ol>
            <div class="pull-right">
                <i class="livicon icon3" data-name="edit" data-size="20" data-loop="true" data-c="#3d3d3d" data-hc="#3d3d3d"></i> Mis Referidos 
            </div>
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container contain_body">
    <div class="row">
        
        <div class="col-sm-12">
            

            <a  href="{{ url('misamigos') }}" class=" btn-large ">
                <div class="row">
                <div class="col-sm-12" style="height: 5em;" ><i class="fa fa-user"></i></div>
                <div class="col-sm-12">Mis Amigos</div>
                </div>
        

            </a>
            
            <a href="{{ url('miscompras') }}" class=" btn-large ">
                <div class="row">
                <div class="col-sm-12" style="height: 5em;" ><i class="fa fa-shopping-cart"></i></div>
                <div class="col-sm-12">Mis Compras</div>
                </div>
        

            </a>

            <a href="{{ url('clientes/amigos') }}" class=" btn-large ">
                <div class="row">
                <div class="col-sm-12" style="height: 5em;" ><i class="fa fa-send"></i></div>
                <div class="col-sm-12">Mis Invitaciones</div>
                </div>
        

            </a>

            

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


        


    </script>
@stop