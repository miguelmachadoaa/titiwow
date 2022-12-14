@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Reporte de Venta por Productos/Combos
@parent
@stop

@section('header_styles')

  <link href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ secure_asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" />
    <link href="{{ secure_asset('assets/vendors/pickadate/css/default.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ secure_asset('assets/vendors/pickadate/css/default.date.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ secure_asset('assets/vendors/pickadate/css/default.time.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ secure_asset('assets/vendors/airDatepicker/css/datepicker.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ secure_asset('assets/vendors/flatpickrCalendar/css/flatpickr.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ secure_asset('assets/css/pages/adv_date_pickers.css') }}" rel="stylesheet" type="text/css"/>
@stop




{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>Reporte de Venta por Productos/Combos </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Reportes </a></li>
        <li class="active">Venta por Productos/Combos</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Reporte Venta por Productos/Combos
                    </h4>
                </div>
                <br />
                <div class="panel-body">

                    <form class="form-horizontal" role="form" method="post" action="{{ secure_url('admin/reportes/exportproductos') }}">

                        {{ csrf_field() }}

                <div class="row">   

                   <div class="form-group col-sm-12 ">
                        <label for="select21" class="col-md-2 control-label text-right">
                        Producto                                               
                        </label>
                        <div class="col-md-10">   
                            <select id="producto" name="producto" class="form-control select2 filtro">
                                <option value="0">Todos</option>
                                
                                 @foreach($productos as $producto)
                                 
                                    <option  value="{{ $producto->id }}">{{ $producto->nombre_producto }}</option>

                                 @endforeach
                                
                            </select>
                        </div>           
                    </div>

                    <div class="form-group col-sm-12 ">
                        <label for="select21" class="col-md-2 control-label text-right">
                        Marca                                               
                        </label>
                        <div class="col-md-10">   
                            <select id="marca" name="marca" class="form-control select2 filtro">
                                <option value="0">Todos</option>
                                
                                 @foreach($marcas as $marca)
                                 
                                    <option  value="{{ $marca->id }}">{{ $marca->nombre_marca }}</option>

                                 @endforeach
                                
                            </select>
                        </div>           
                    </div>


                    <div class="form-group col-sm-12 ">
                        <label for="select21" class="col-md-2 control-label text-right">
                        Categorias                                               
                        </label>
                        <div class="col-md-10">   
                            <select id="categoria" name="categoria" class="form-control select2 filtro">
                                <option value="0">Todos</option>
                                
                                 @foreach($categorias as $categoria)
                                 
                                    <option  value="{{ $categoria->id }}">{{ $categoria->nombre_categoria }}</option>

                                 @endforeach
                                
                            </select>
                        </div>           
                    </div>

                    <div class="form-group col-sm-12 ">
                        <label for="select21" class="col-md-2 control-label text-right">
                        Almacenes                                               
                        </label>
                        <div class="col-md-10">   
                            <select id="almacen" name="almacen" class="form-control select2 ">
                                <option value="0">Todos</option>
                                
                                 @foreach($almacenes as $almacen)
                                 
                                    <option  value="{{ $almacen->id }}">{{ $almacen->nombre_almacen }}</option>

                                 @endforeach
                                
                            </select>
                        </div>           
                    </div>



                    <div class="form-group col-sm-12 ">
                        <label for="select21" class="col-md-2 control-label text-right">
                        Tipo de Producto                                          
                        </label>
                        <div class="col-md-10">   
                            <select id="agrupar" name="agrupar" class="form-control select2">
                                <option value="0">Normal</option>
                                <option value="1">Combo</option>
                            </select>
                        </div>           
                    </div>


                    <div class="form-group">

                                <label class="col-md-2 control-label text-right">Desde - Hasta:</label>

                        <div class="row">
                                <div class="col-sm-5 pad-0-res mt-5">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="livicon" data-name="calendar" data-size="16" data-c="#555555"
                                           data-hc="#555555" data-loop="true"></i>
                                    </div>
                                    <input required class="form-control" id="desde" name="desde" placeholder="Desde">
                                </div>
                            </div>
                                <div class="col-sm-5 pad-0-res mt-5">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="livicon" data-name="calendar" data-size="16" data-c="#555555"
                                           data-hc="#555555" data-loop="true"></i>
                                    </div>
                                    <input required class="form-control" id="hasta" name="hasta" placeholder="Hasta">
                                </div>
                            </div>
                        </div>
                            <!-- /.input group -->
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="alert alert-danger">Por favor no generar reportes con rango de fechas mayor a 30 dias.</div>
                        </div>
                    </div>



                    

                    <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="submit" class="btn btn-md btn-primary">  Descargar ventas en Excel  </button>
                                
                              

                                <a class="btn btn-md btn-danger" href="{{ route('admin.dashboard') }}">
                                    Cancelar
                                </a>
                            
                            </div>
                        </div>

                </div>

                </form>
                </div>
            </div>
        </div>
    </div>    <!-- row-->
</section>




@stop



{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
    <script src="{{ secure_asset('assets/vendors/pickadate/js/picker.js') }}" type="text/javascript"></script>
    <script src="{{ secure_asset('assets/vendors/pickadate/js/picker.date.js') }}" type="text/javascript"></script>
    <script src="{{ secure_asset('assets/vendors/pickadate/js/picker.time.js') }}" type="text/javascript"></script>
    <script src="{{ secure_asset('assets/vendors/flatpickrCalendar/js/flatpickr.min.js') }}" type="text/javascript"></script>
    <script src="{{ secure_asset('assets/vendors/airDatepicker/js/datepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ secure_asset('assets/vendors/airDatepicker/js/datepicker.en.js') }}" type="text/javascript"></script>

<script src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>

   
   <script type="text/javascript">
       

    var desde = flatpickr("#desde");
    
    var hasta = flatpickr("#hasta");

    desde.set("onChange", function (d) {
        hasta.set("minDate", d.fp_incr(1)); //increment by one day
    });
    hasta.set("onChange", function (d) {
        desde.set("maxDate", d);
    });


     $(document).ready(function(){
        var s2=$('.select2').select2({
            placeholder: "select",
            theme:"bootstrap"
        });

        $('.filtro').change(function(){
        id=$(this).attr('id');
        v=$(this).val();
        $('.filtro').select2("destroy");
        $('.filtro').val(0);
        $(this).val(v);
        $('.filtro').select2();

    });

    })


   


   </script>

@stop