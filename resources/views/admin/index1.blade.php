@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    Escritorio
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')

    <link href="{{ secure_asset('assets/vendors/fullcalendar/css/fullcalendar.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ secure_asset('assets/css/pages/calendar_custom.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" media="all" href="{{ secure_asset('assets/vendors/bower-jvectormap/css/jquery-jvectormap-1.2.2.css') }}"/>
    <link rel="stylesheet" href="{{ secure_asset('assets/vendors/animate/animate.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('assets/css/pages/only_dashboard.css') }}"/>
    <meta name="_token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/smalotDatepicker/css/bootstrap-datetimepicker.min.css') }}">

@stop

{{-- Page content --}}
@section('content')

    <section class="content-header">
        <h1>Bienvenido al Backend</h1>
        <ol class="breadcrumb">
            <li class="active">
                <a href="#">
                    <i class="livicon" data-name="home" data-size="16" data-color="#333" data-hovercolor="#333"></i>
                    Escritorio
                </a>
            </li>
        </ol>
    </section>

    <input type="hidden" name="input_clientes" id="input_clientes" value="{{ $clientes  }}">
    <input type="hidden" name="input_productos" id="input_productos" value="{{ $productos  }}">
    <input type="hidden" name="input_usuarios" id="input_usuarios" value="{{ $usuarios  }}">
    <input type="hidden" name="input_ordenes" id="input_ordenes" value="@if(isset($ordenes_mes->count_row)) {{ $ordenes_mes->count_row  }} @else {{ 0 }} @endif">

    <div id="data" data-info="{{ json_encode($data_info) }}"></div>


    <section class="content">



         @if (session('aviso'))
                        <div class="alert alert-danger">
                            {{ session('aviso') }}
                        </div>
                    @endif

                    
    @if (Sentinel::getUser()->hasAnyAccess(['productos.*']))
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 margin_10 animated fadeInLeftBig">
                <!-- Trans label pie charts strats here-->
                <div class="lightbluebg no-radius">
                    <div class="panel-body squarebox square_boxs">
                        <div class="col-xs-12 pull-left nopadmar">
                            <div class="row">
                                <div class="square_box col-xs-7 text-right">
                                    <span> Clientes Registrados </span>

                                    <div class="number" id="myTargetElement1"></div>
                                </div>
                                <i class="livicon  pull-right" data-name="users" data-l="true" data-c="#fff"
                                   data-hc="#fff" data-s="70"></i>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <small class="stat-label">Mes</small>
                                    <h4 id="myTargetElement1.1"></h4>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <small class="stat-label">Año</small>
                                    <h4 id="myTargetElement1.2"></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 margin_10 animated fadeInUpBig">
                <!-- Trans label pie charts strats here-->
                <div class=" palebluecolorbg no-radius">
                    <div class="panel-body squarebox square_boxs">
                        <div class="col-xs-12 pull-left nopadmar">
                            <div class="row">
                                <div class="square_box col-xs-7 pull-left">
                                    <span>Monto de Ventas Hoy</span>

                                    <div class="number" id="myTargetElement2"></div>
                                </div>
                                <i class="livicon pull-right" data-name="shopping-cart" data-l="true" data-c="#fff"
                                   data-hc="#fff" data-s="70"></i>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <small class="stat-label">Mes </small>
                                    <h4 id="myTargetElement2.1"></h4>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <small class="stat-label">Año</small>
                                    <h4 id="myTargetElement2.2"></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6 margin_10 animated fadeInDownBig">
                <!-- Trans label pie charts strats here-->
                <div class="goldbg no-radius">
                    <div class="panel-body squarebox square_boxs">
                        <div class="col-xs-12 pull-left nopadmar">
                            <div class="row">
                                <div class="square_box col-xs-7 pull-left">
                                    <span>Cantidad de Ventas Hoy</span>

                                    <div class="number" id="myTargetElement3"></div>
                                </div>
                                <i class="livicon pull-right" data-name="inbox" data-l="true" data-c="#fff"
                                   data-hc="#fff" data-s="70"></i>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <small class="stat-label">Mes</small>
                                    <h4 id="myTargetElement3.1"></h4>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <small class="stat-label">Año</small>
                                    <h4 id="myTargetElement3.2"></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 margin_10 animated fadeInRightBig">
                <!-- Trans label pie charts strats here-->
                <div class="redbg no-radius">
                    <div class="panel-body squarebox square_boxs">
                        <div class="col-xs-12 pull-left nopadmar">
                            <div class="row">
                                <div class="square_box col-xs-7 pull-left">
                                    <span>Clientes Inactivo Hoy</span>

                                    <div class="number" id="myTargetElement4"></div>
                                </div>
                                <i class="livicon pull-right" data-name="users" data-l="true" data-c="#fff"
                                   data-hc="#fff" data-s="70"></i>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <small class="stat-label">Mes</small>
                                    <h4 id="myTargetElement4.1"></h4>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <small class="stat-label">Año</small>
                                    <h4 id="myTargetElement4.2"></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/row-->


    @else
        <div class="row">
            <div class="col-md-6 col-lg-6 col-12 my-3">
                <div class="card panel-primary">
                    <div class="card-heading">
                        <h4 class="card-title">Amigo Alpinista</h4>
                    </div>
                    <div class="card-body">
                        <p>
                            En este Backend Podrás controlar el Proceso de venta de AlpinaGo!
                        </p>
                    </div>
                </div>
            </div>
            
        </div>
     @endif 
    </section>
    <div class="modal fade" id="editConfirmModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Alert</h4>
                </div>
                <div class="modal-body">
                    <p>You are already editing a row, you must save or cancel that row before edit/delete a new row</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script type="text/javascript" src="{{ secure_asset('assets/vendors/moment/js/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ secure_asset('assets/vendors/smalotDatepicker/js/bootstrap-datetimepicker.min.js') }}"></script>

    <!-- EASY PIE CHART JS -->
    <script src="{{ secure_asset('assets/vendors/bower-jquery-easyPieChart/js/easypiechart.min.js') }}"></script>
    <script src="{{ secure_asset('assets/vendors/bower-jquery-easyPieChart/js/jquery.easypiechart.min.js') }}"></script>
    <script src="{{ secure_asset('assets/vendors/bower-jquery-easyPieChart/js/jquery.easingpie.js') }}"></script>
    <!--for calendar-->
    <script src="{{ secure_asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ secure_asset('assets/vendors/fullcalendar/js/fullcalendar.min.js') }}" type="text/javascript"></script>
    <!--   Realtime Server Load  -->
    <script src="{{ secure_asset('assets/vendors/flotchart/js/jquery.flot.js') }}" type="text/javascript"></script>
    <script src="{{ secure_asset('assets/vendors/flotchart/js/jquery.flot.resize.js') }}" type="text/javascript"></script>
    <!--Sparkline Chart-->
    <script src="{{ secure_asset('assets/vendors/sparklinecharts/jquery.sparkline.js') }}"></script>
    <!-- Back to Top-->
    <script type="text/javascript" src="{{ secure_asset('assets/vendors/countUp_js/js/countUp.js') }}"></script>
    <!--   maps -->
    <script src="{{ secure_asset('assets/vendors/bower-jvectormap/js/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ secure_asset('assets/vendors/bower-jvectormap/js/jquery-jvectormap-world-mill-en.js') }}"></script>
    <!--  todolist-->
    <script src="{{ secure_asset('assets/js/pages/dashboard.js') }}" type="text/javascript"></script>

@stop