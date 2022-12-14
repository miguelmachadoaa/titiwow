@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Reporte error iva       
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
    <h1>Reporte de error iva   </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Reportes </a></li>
        <li class="active">Carritos error iva   </li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Reporte de error iva  
                    </h4>
                </div>
                <br />
                <div class="panel-body">

                    <form class="form-horizontal" role="form" method="post" action="{{ secure_url('admin/reportes/exporterroriva') }}">

                        {{ csrf_field() }}

                <div class="row">   
                        


                    <div class="form-group">

                                <label class="col-md-2 control-label text-right">Seleccione el dia:</label>

                        <div class="row">
                                
                                <div class="col-sm-10 pad-0-res mt-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="livicon" data-name="calendar" data-size="16" data-c="#555555"
                                           data-hc="#555555" data-loop="true"></i>
                                    </div>
                                    <input type="text" required class="form-control" id="hasta" name="hasta" placeholder="Hasta">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            
                            <div class="form-group col-sm-12 ">

                                    <br>
                                    <label for="select21" class="col-md-2 control-label text-right">
                                    Almacen                                               
                                    </label>
                                    <div class="col-md-10">   
                                        <select id="id_almacen" name="id_almacen" class="form-control select2">
                                            <option value="">Seleccione</option>


                                             @foreach($almacenes as $alm)
                                             
                                                <option  value="{{ $alm->id }}">{{ $alm->nombre_almacen }}</option>

                                             @endforeach
                                            
                                        </select>
                                    </div>           
                                </div>
                        </div>

                            <!-- /.input group -->
                     </div>
                    

                    <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="submit" class="btn btn-md btn-primary">  Descargar  Excel  </button>

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
       

    
    var hasta = flatpickr("#hasta");

     $(document).ready(function(){
        $('.select2').select2({
            placeholder: "select",
            theme:"bootstrap"
        });
    })



   </script>

@stop