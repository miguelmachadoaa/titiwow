@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
 Clientes
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
    <h1>Reporte de  Clientes</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Reportes </a></li>
        <li class="active"> Clientes</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Reporte  Clientes
                    </h4>
                </div>
                <br />
                <div class="panel-body">

                    <form class="form-horizontal" role="form" method="post" action="{{ secure_url('admin/reportes/exportclientes') }}">

                        {{ csrf_field() }}

                <div class="row">   
                    


                    <div class="form-group">

                        <div class="row">

                                <div class="form-group col-sm-6 col-xs-12" style="margin: 0 0 15px 0;">
                                        
                                        <label for="select21" class=" control-label">Departamento</label>
                                            
                                            <div class="" >

                                                    <select id="state_id" name="state_id" class="form-control select2">

                                                        <option value="">Seleccione</option>
                                                        
                                                        @foreach($states as $state)

                                                        <option value="{{ $state->id }}">
                                                                {{ $state->state_name}}</option>
                                                        @endforeach
                                                        
                                                      
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group col-sm-6 col-xs-12" style="margin: 0 0 15px 0;">
                                                
                                                <label for="select21" class=" control-label">Ciudad</label>
                                                <div class="" >

                                                    <select id="city_id" name="city_id" class="form-control select2">
                                                        <option value="">Seleccione</option>
                                                      
                                                    </select>

                                                </div>

                                            </div>
                        </div>
                            <!-- /.input group -->
                    </div>
                    

                    <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="submit" class="btn btn-md btn-primary">  Descargar Excel  </button>

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


<input type="hidden" name="base" id="base" value="{{secure_url('/')}}">

@stop



{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->

<script src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>

   
   <script type="text/javascript">
       
     $(document).ready(function(){
        $('.select2').select2({
            placeholder: "select",
            theme:"bootstrap"
        });
    })


      $('select[name="state_id"]').on('change', function() {
                    var stateID = $(this).val();
                var base = $('#base').val();

                    if(stateID) {
                        $.ajax({
                            url: base+'/configuracion/cities/'+stateID,
                            type: "GET",
                            dataType: "json",
                            success:function(data) {

                                
                                $('select[name="city_id"]').empty();
                                $.each(data, function(key, value) {
                                    $('select[name="city_id"]').append('<option value="'+ key+'_'+value +'">'+ value +'</option>');
                                });

                            }
                        });
                    }else{
                        $('select[name="city_id"]').empty();
                    }
                });





   </script>

@stop