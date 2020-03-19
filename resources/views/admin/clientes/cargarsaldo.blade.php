@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Cargar Saldo Clientes
@parent
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
    <h1>Cargar Saldo Clientes</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Saldo Clientes </a></li>
        <li class="active">Cargar Saldo Clientes</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Cargar  Saldo Clientes
                    </h4>
                </div>
                <br />
                <div class="panel-body">
                <form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="{{ secure_url('admin/clientes/importsaldo') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="form-group">


                             <div class="row form-group">
                                <label class="col-md-3 col-lg-3 col-12 control-label" for="upload">Fecha de vencimiento</label>
                                <div class="col-md-9 col-12 col-lg-9">
                                    <input class="form-control" type="date"  name="fecha_vencimiento" id="fecha_vencimiento"> <!-- rename it -->
                                </div>
                            </div>

                            <br>


                            <div class="row form-group">
                                <label class="col-md-3 col-lg-3 col-12 control-label" for="upload">Archivo XLSX (Suministrado)</label>
                                <div class="col-md-9 col-12 col-lg-9">
                                    <input type="file" accept=".xlsx" name="file_alpinistas" name="file_alpinistas" id="clientes"> <!-- rename it -->
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-8">
                                    
                                    <a class="btn btn-md btn-danger" href="{{ secure_url('admin/clientes') }}">
                                        Cancelar
                                    </a>

                                    <button type="submit" class="btn btn-success">
                                        Cargar Saldo Clientes
                                    </button>
                                
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>    <!-- row-->
</section>




@stop
@section('footer_scripts')

<script>
        
   
</script>
@stop
