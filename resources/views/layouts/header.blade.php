<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <title>
    	@section('title')
        | Alpina
        @show
    </title>
    <!--global css starts-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/lib.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Comfortaa|Oswald|Montserrat:300,400,600|Roboto" rel="stylesheet">    <!--end of global css-->
    <!--page level css-->
    @yield('header_styles')
    <!--end of page level css-->
</head>

<body>
    <!-- Header Start -->
    <header>
        <!-- Icon Section Start -->
        <div class="icon-section">
            <div class="container">
                <div class="col-sm-4 hidden-md" >
                    <div class="navbar-header menu_float">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#collapse">
                            <span><a href="#"><i class="livicon" data-name="responsive-menu" data-size="25" data-loop="false" data-c="#ffffff" data-hc="#ccc"></i>
                            </a></span>
                        </button>
                    </div>
                    <div class="pull-left">
                        <ul class="list-inline icon-position">
                            <li><a href="https://www.facebook.com/alpina" target="_blank"><i class="fa fa-facebook-f color-top" ></i></a></li>
                            <li><a href="https://www.instagram.com/alpinacol/" target="_blank"><i class="fa fa-instagram color-top" ></i></a></li>
                            <li><a href="https://www.linkedin.com/company/alpina/?trk=vsrp_companies_res_name&trkInfo=VSRPsearchId%3A3286542181450727911739%2CVSRPtargetId%3A48174%2CVSRPcmpt%3Aprimary" target="_blank"><i class="fa fa-linkedin-square color-top" ></i></a></li>
                            <li><a href="https://twitter.com/Alpina" target="_blank"><i class="fa fa-twitter color-top" ></i></a></li>
                            <li><a href="https://www.youtube.com/user/AlpinaSA" target="_blank"><i class="fa fa-youtube-play color-top" ></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-8 col-sm-8" >
                    <ul class="list-inline">
                        <li class="pull-right menu-top">
                            <ul class="list-inline icon-position">
                                <li>
                                    <a href="#"><i class="shopping-cart" data-name="ion-ios7-cart" data-size="18" data-loop="true" data-c="#fff" data-hc="#fff"></i></a>
                                    
                                    <label class="hidden-xs"><a id="detalle_carro_front" href="{{url('cart/show')}}" >Carrito de Compra</a></label>

                                    <label class="hidden-xs"><a id="detalle_carro_front" href="{{url('clientes')}}">Area de Clientes</a></label>
                                </li>
        
                            {{--based on anyone login or not display menu items--}}
                            @if(Sentinel::guest())
                                <li>
                                    <a href="#"><i class="shopping-cart" data-name="ion-ios7-cart" data-size="18" data-loop="true" data-c="#fff" data-hc="#fff"></i></a>               
                                    <label ><a id="detalle_carro_front" href="{{url('login')}}" >Iniciar Sesión</a></label>    
                                </li>
                                <li>
                                    <a href="#"><i class="shopping-cart" data-name="ion-ios7-cart" data-size="18" data-loop="true" data-c="#fff" data-hc="#fff"></i></a>               
                                    <label ><a id="detalle_carro_front" href="{{url('register')}}" >Registrarse</a></label>    
                                </li>
                            @else
                                <li {{ (Request::is('my-account') ? 'class=active' : '') }}><a href="{{ URL::to('my-account') }}">Mi Cuenta
                                <li><a href="{{ URL::to('logout') }}">Cerrar Sesión</a>
                                </li>
                            @endif
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- //Icon Section End -->
        <!-- logo Section Start -->
        <div class="logo-section">
            <div class="logo-section-height">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 hidden-xs" >
                            <div id="search_block_top" class="pull-left">
                                <button type="submit" name="submit_search" class="btn btn-default button-search">
                                    <span>Buscar</span>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-8">
                            <div id="header_logo" class="pull-center">
                                <a  href="{{ route('home') }}"><img src="{{ asset('assets/images/logo_alpina.jpg') }}" alt="Alpina Market" class="logo_position"></a>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-4" class="pull_left">
                            <div id="cart_block_top" class="pull-right">
                                <button type="submit" name="submit_search" class="btn btn-default button-cart">
                                    <span>Buscar</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- //logo Section End -->
<nav class="navbar navbar-default container">

<div class="collapse navbar-collapse" id="collapse">
    <ul class="nav navbar-nav navbar-left">
            <ul class="nav navbar-nav">
                @foreach ($menus as $key => $item)
                    @if ($item['parent'] != 0)
                        @break
                    @endif
                    @include('layouts.menu', ['item' => $item])
                @endforeach
            </ul>
    </div>
</nav>

        <!-- Nav bar End -->
    </header>
    <!-- //Header End -->