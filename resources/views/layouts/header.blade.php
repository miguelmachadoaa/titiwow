<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ secure_asset('assets/img/favicon/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ secure_asset('assets/img/favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ secure_asset('assets/img/favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="{{ secure_asset('assets/img/favicon/site.webmanifest')}}">
    <link rel="mask-icon" href="{{ secure_asset('assets/img/favicon/safari-pinned-tab.svg')}}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

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
    @yield('meta_tags')
    <!--global css starts-->
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/lib.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Comfortaa|Oswald|Montserrat:300,400,600|Roboto" rel="stylesheet">    
    <link href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />
    <!--end of global css-->
    <!--page level css-->
    @yield('header_styles')
    <!--end of page level css-->
        @if (App::environment('local')) 

            <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=UA-129370910-1"></script>
            <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-129370910-1');
            </script>
            <!-- Google Tag Manager -->
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-PG9RTMH');</script>
            <!-- End Google Tag Manager -->
        @endif
</head>

<body>
    @if (App::environment('local')) 
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PG9RTMH"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
    @endif
    <!-- Header Start -->
    <header> 
        <!-- Icon Section Start -->
        <div class="icon-section">
            <div class="container">
                <div class="row hidden-lg">
                    <div class="col-sm-8" >
                        <div id="header_logo" class="pull-left hidden-lg">
                                <a  href="{{ secure_url('/') }}" class="hidden-md"><img src="{{ secure_asset('assets/images/logo_movil.png') }}" alt="Alpina Go!" class="logo_position"></a>
                        </div>
                    </div>
                    <div class="col-sm-4 pull-right" >
                    <button type="button" class="navbar-toggle collapsed pull-left" data-toggle="collapse" data-target="#collapse">
                            <span><a href="#"><i class="livicon" data-name="responsive-menu" data-size="25" data-loop="false" data-c="#ffffff" data-hc="#ccc"></i>
                            </a></span>
                        </button>
                    </div>
                </div>
                <div class="col-sm-4 " >
                   
                    <div class="pull-left hidden-md hidden-xs hidden-sm">
                        <ul class="list-inline icon-position" style="margin-bottom:0px !important">
                            <li><a href="https://www.facebook.com/alpina" target="_blank"><i class="fa fa-facebook-f color-top" ></i></a></li>
                            <li><a href="https://www.instagram.com/alpinacol/" target="_blank"><i class="fa fa-instagram color-top" ></i></a></li>
                            <li><a href="https://www.linkedin.com/company/alpina/?trk=vsrp_companies_res_name&trkInfo=VSRPsearchId%3A3286542181450727911739%2CVSRPtargetId%3A48174%2CVSRPcmpt%3Aprimary" target="_blank"><i class="fa fa-linkedin-square color-top" ></i></a></li>
                            <li><a href="https://twitter.com/Alpina" target="_blank"><i class="fa fa-twitter color-top" ></i></a></li>
                            <li><a href="https://www.youtube.com/user/AlpinaSA" target="_blank"><i class="fa fa-youtube-play color-top" ></i></a></li>
                        <li class="menu-top">
                            <label ><a id="detalle_carro_front" href="https://www.alpina.com" target="_blank">Ir Alpina Digital</a></label>    
                        </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-8 col-sm-8 hidden-md hidden-xs hidden-sm" id="datos">
                    <ul class="list-inline">
                        <li class="pull-right menu-top">
                            <ul class="list-inline icon-position">

                                <li >
                                    
                                    <label class="ubicacion_header"><a id="ubicacion_header"  >Seleccione Ciudad</a></label>
                                   
                                </li>
        
                            {{--based on anyone login or not display menu items--}}
                            @if(Sentinel::guest())
                                
                                <li>
                                    <a href="#"><i class="shopping-cart" data-name="ion-ios7-cart" data-size="18" data-loop="true" data-c="#fff" data-hc="#fff"></i></a>               
                                    <label ><a id="detalle_carro_front" href="{{secure_url('login')}}" >Iniciar Sesión</a></label>    
                                </li>
                                
                                <li>
                                    <a href="#"><i class="shopping-cart" data-name="ion-ios7-cart" data-size="18" data-loop="true" data-c="#fff" data-hc="#fff"></i></a>               
                                    <label ><a id="detalle_carro_front" href="{{secure_url('registro')}}" >Registrarse</a></label>    
                                </li>
                            @else
                                <li {{ (Request::is('clientes') ? 'class=active' : '') }}>
                                    <label >                          
                                        <a  href="{{secure_url('clientes')}}">
                                            Hola: {{ Sentinel::getUser()->first_name }} {{ Sentinel::getUser()->last_name }}</a>
                                    </label>  
                                </li>

                                <li>
                                    <label ><a href="{{ secure_url('logout') }}">Cerrar Sesión</a></label>  
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
        <div class="logo-section hidden-xs hidden-sm hidden-md">
            <div class="logo-section-height">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 " >
                            <div id="search_block_top" class="pull-left">
                                <form method="GET" action="{{ secure_url('buscar') }}">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <input type="text" name="buscar"  id="buscar" class="form-control" placeholder="Buscar ..." value="{{ old('buscar') }}" autocomplete="off">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" alt="Buscar" ><i class="fa fa-search" aria-hidden="true" id="busqueda"></i></button>
                                            </span>
                                            </div><!-- /input-group -->
                                        </div><!-- /.col-lg-6 -->
                                    </div>  
                                </form>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-8">
                            <div id="header_logo" class="pull-center">
                                <a  href="{{ secure_url('/') }}"><img src="{{ secure_asset('assets/images/logo_go.png') }}" alt="Alpina Go!" class="logo_position"></a>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-4 navbar-nav" class="pull_left">
                            <div id="cart_block_top" class="pull-right navbar-nav">
                                

                                <!--button type="button" name="submit_search" class="btn btn-default button-cart dropdown nav-item "-->
                                
                                    <a class="btn btn-default" role="button"  aria-expanded="false"  href="{{ secure_url('cart/show') }}" alt="Ir a Mi Carrito de Compras"><i class="fa fa-cart-arrow-down" aria-hidden="true" id="carrto"></i></a>
 
                                <!--/button-->


                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- //logo Section End -->
        
        <nav class="navbar navbar-default container">
            <div class="collapse navbar-collapse" id="collapse"> 
                <ul class="nav navbar-nav">
                    <li style="padding: 0.2em 1em;" class="hidden-lg">

                        <form method="GET" action="{{ secure_url('buscar') }}">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <input type="text" name="buscar"  id="buscar" class="form-control" placeholder="Buscar ..." value="{{ old('buscar') }}" autocomplete="off">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" alt="Buscar" ><i class="fa fa-search" aria-hidden="true" id="busqueda"></i></button>
                                            </span>
                                            </div><!-- /input-group -->
                                        </div><!-- /.col-lg-6 -->
                                    </div>  
                                </form>
                        
                    </li>
                    <hr class="hidden-lg">

                    @foreach ($menus as $key => $item)
                        @if ($item['parent'] != 0)
                            @break
                        @endif
                        @include('layouts.menu-item', ['item' => $item])
                    @endforeach

                    <hr />



                        <li class="hidden-lg ubicacion_header">       
                            <a href="#"  ></a>
                        </li>
                    
                    {{--based on anyone login or not display menu items--}}
                        @if(Sentinel::guest())
                        <li class="hidden-lg">
                            <a href="{{secure_url('login')}}" >Iniciar Sesión</a>   
                        </li> 
                        <li class="hidden-lg">
                            <a href="{{secure_url('registro')}}" >Registrarse</a>   
                        </li>
                    @else
                        <li class="hidden-lg" {{ (Request::is('clientes') ? 'class=active' : '') }} >                         
                                <a  href="{{secure_url('clientes')}}">
                                    Hola: {{ Sentinel::getUser()->first_name }} {{ Sentinel::getUser()->last_name }}</a>
                        </li>
                        <li class="hidden-lg">
                           <a href="{{ secure_url('logout') }}">Cerrar Sesión</a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>

        <!-- Nav bar End -->
    </header>
    <!-- //Header End -->