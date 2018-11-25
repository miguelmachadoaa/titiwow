@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Cargar Alpinistas
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
    <h1>Cargar Alpinistas</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Alpinistas </a></li>
        <li class="active">Cargar Alpinistas</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Cargar Nuevos Alpinistas
                    </h4>
                </div>
                <br />
                <div class="panel-body">
                <form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="{{ secure_url('admin/alpinistas/import') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 col-lg-3 col-12 control-label" for="upload">Archivo CSV (Documento,Código Alpinista)</label>
                                <div class="col-md-9 col-12 col-lg-9">
                                    <input type="file" accept=".csv" name="file_alpinistas" name="alpinista" id="alpinista"> <!-- rename it -->
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <button type="submit" class="btn btn-success">
                                        Cargar Alpinistas
                                    </button>
                                    <a class="btn btn-md btn-danger" href="{{ route('admin.alpinistas.index') }}">
                                        Cancelar
                                    </a>
                                
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
