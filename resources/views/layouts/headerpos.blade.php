<!doctype html>
<html lang="en">
  <head>
     <title>
        @section('title')
        | Titiwow
        @show
    </title>
    @yield('meta_tags')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
        
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">


    <link href="https://fonts.googleapis.com/css?family=Comfortaa|Oswald|Montserrat:300,400,600|Roboto" rel="stylesheet">    
    

    <link href="{{ secure_asset('assets/sidebar/css/style.css') }}" rel="stylesheet" />

      @yield('header_styles')

  </head>
  <body>
        
        <div class="wrapper d-flex align-items-stretch">
            <nav id="sidebar" class="active">
                <h1><a href="index.html" class="logo"><img src="{{ secure_asset('assets/images/logo_movil.png') }}" alt="" style="width: 100%;"></a></h1>
        <ul class="list-unstyled components mb-5">
          <li class="active">
            <a href="#"><span class="fa fa-home"></span> Tablero</a>
          </li>
          <li>
              <a href="#"><span class="fa fa-floppy-o"></span> Carrito</a>
          </li>
          <li>
            <a href="#"><span class="fa fa-shopping-cart"></span> Pedidos</a>
          </li>
          <li>
            <a href="#"><span class="fa fa-usd"></span> Transacciones</a>
          </li>
          <li>
            <a href="#"><span class="fa fa-area-chart"></span> Reportes</a>
          </li>
          <li>
            <a href="#"><span class="fa fa-sign-out"></span> Salir</a>
          </li>
        </ul>

        <div class="footer">
            <p>
                      Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="icon-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib.com</a>
                    </p>
        </div>
        </nav>

         <div id="content" class="p-1 p-md-1">

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <div class="container-fluid">

            <button type="button" id="sidebarCollapse" class="btn btn-primary">
              <i class="fa fa-bars"></i>
              <span class="sr-only">Toggle Menu</span>
            </button>
            <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="nav navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Portfolio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact</a>
                </li>
              </ul>
            </div>
          </div>
        </nav>

