@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Cargar Precios Base
@parent
@stop




@section('header_styles')

    <link href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />

    <link href="{{ secure_asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" />

@stop

{{-- page level styles --}}
@section('header_styles')

    <style>

    .container{
        margin-top:20px;
    }
    .image-preview-input {
            position: relative;
            overflow: hidden;
            margin: 0px;
            color: #333;
            background-color: #fff;
            border-color: #ccc;
        }
        .image-preview-input input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            margin: 0;
            padding: 0;
            font-size: 20px;
            cursor: pointer;
            opacity: 0;
            filter: alpha(opacity=0);
        }
        .image-preview-input-title {
            margin-left:2px;
        }
        .image_radius{
            border-top-right-radius: 4px !important;
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
            border-bottom-right-radius: 4px !important;
        }
    .fileinput .thumbnail > img{
        width:100%;
    }
    .color_a{
        color: #333;
    }
    .btn-file > input{
        width: auto;
    }
    </style>
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>Cargar Precios Base</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Productos </a></li>
        <li class="active">Cargar Precios Base</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Cargar Precios Base de Productos
                    </h4>
                </div>
                <br />
                <div class="panel-body">
                <form class="" enctype="multipart/form-data" role="form" method="post" action="{{ secure_url('admin/productos/postpreciobase') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                    <div class="row">
                        
                         




                    </div>

                       
                  <div class="clearfix"></div>


                        <div class="form-group {{ $errors->
                            first('file', 'has-error') }}">
                            <div class="row" style="margin-top: 1em;">
                                <label class="col-md-3 col-lg-3 col-12 control-label" for="upload">Archivo XLSX (Referencia, Precio Base, PUM)</label>


                                <div class="col-md-9 col-12 col-lg-9">
                                    <input type="file" accept=".xlsx" name="file_update"  id="file_update"> <!-- rename it -->


                                    {!! $errors->first('file', '<span class="help-block">:message</span> ') !!}


                                </div>

                                <div class="col-sm-12">
                                    <a class="btn btn-link" target="_blank" href="{{secure_url('uploads/files/libro_productos_import.xlsx')}}">Descargar Archivo de Muestra</a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-8">
                                    
                                    <a class="btn btn-md btn-danger" href="{{ secure_url('admin/productos/cargarupdate') }}">
                                        Cancelar
                                    </a>

                                    <button type="submit" class="btn btn-success">
                                        Cargar Archivo
                                    </button>
                                
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>    <!-- row-->
</section>

<input type="hidden" name="base" id="base" value="{{  secure_url('/') }}">

@stop
@section('footer_scripts')


<script type="text/javascript" src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>

    <script language="javascript" type="text/javascript" src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}"></script>



<script>


   
</script>
@stop
